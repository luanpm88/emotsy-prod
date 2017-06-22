<?php

/**
 * Campaign class.
 *
 * Model class for campaigns related functionalities.
 * This is the center of the application
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Log as MailLog;
use Acelle\Library\StringHelper;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use DB;
use Carbon\Carbon;
use Acelle\Model\SystemJob;
use Acelle\Exceptions\CampaignPausedException;
use Acelle\Exceptions\CampaignErrorException;

class Campaign extends AbsCampaign
{
    /**
     * Start the campaign
     *
     */
    public function start($trigger = null) {
        try {
            // make sure the same campaign is not started twice
            if (!$this->refreshStatus()->isReady()) {
                MailLog::info("Campaign ID: {$this->id} is not ready or already started");
                return;
            }

            // lock the campaign, marked as sending
            $this->sending();
            MailLog::info('Starting campaign `'.$this->name.'`');

            // Reset max_execution_time so that command can run for a long time without being terminated
            self::resetMaxExecutionTime();

            // Only run multi-process if pcntl is enabled
            $processes = (int) $this->customer->getOption('max_process');
            if (extension_loaded('pcntl') && $processes > 1) {
                MailLog::info('Run in multi-process mode');
                $this->runMultiProcesses();
            } else {
                MailLog::info('Run in single-process mode');
                $this->run();
            }
            MailLog::info('Finish campaign `'.$this->name.'`');

            // runMultiProcesses() and run() are responsible for setting DONE status
        } catch (\Exception $ex) {
            // Set ERROR status
            $this->error($ex->getMessage());
            MailLog::error('Starting campaign failed. '.$ex->getMessage());
        }
    }

    /**
     * Start the campaign, using PHP fork() to launch multiple processes.
     *
     * @return mixed
     */
    public function runMultiProcesses()
    {
        $count = (int) $this->customer->getOption('max_process');
        $subscribers = $this->getPendingSubscribers();
        MailLog::info(sizeof($subscribers).' subscriber(s) selected');

        // split the big subscribers list into smaller ones
        // this is also the number of processes to fork
        $chunks = $this->getChunks($subscribers, $count);

        MailLog::info('Forking '.sizeof($chunks).' process(es)');
        $parentPid = getmypid();
        $children = [];
        for ($i = 0; $i < sizeof($chunks); ++$i) {
            $pid = pcntl_fork();

            // for child process only
            if (!$pid) {
                // Reconnect to the DB to prevent connection closed issue when using fork
                DB::reconnect('mysql');

                // Re-initialize logging to capture the child process' PID
                MailLog::fork();

                MailLog::info('Start child process '.($i + 1).' of '.sizeof($chunks).' (forked from '.$parentPid.')');
                sleep(self::WORKER_DELAY);
                $this->run($chunks[$i]);

                exit($i + 1);
                // end child process
            } else {
                $children[] = $pid;
            }
        }

        // wait for child processes to finish
        foreach($children as $child) {
            $pid = pcntl_wait($status);
            if (pcntl_wifexited($status)) {
                $code = pcntl_wexitstatus($status);
                MailLog::info("Child process $pid finished, status code: $code");
            } else {
                MailLog::warning("Child process $pid did not normally exit");
                $this->error("Child process $pid did not normally exit");
            }
        }

        // after all child processes are done
        $this->refreshStatus();

        // If all child processes finish sucessfully, just mark campaign as done
        // Otherwise, mark campaign with error status (by child process)
        //
        // There are conventions here:
        //   + Child process does not update status from SENDING to DONE, it is left for the parent process
        //   + Child process only updates status from SENDING to ERROR
        //   + Parent process only updates status to DONE when current status is SENDING
        //     indicating that all child processes finish sucessfully
        //   + In case one child process update the status from SENDING to ERROR, it is left as the final status
        //     the latest error is kept
        if ($this->status == self::STATUS_SENDING) {
            $this->done();
        } else {
            // leave the latest error reported by child process
        }
    }

    /**
     * Send Campaign
     * Iterate through all subscribers and send email.
     */
    public function run($partition = null)
    {
        // check if the method is trigger by a child process (triggered by startMultiProcess method)
        $asChildProcess = !($partition == null);

        // try/catch to make sure child process does not stop without reporting any error
        try {
            // clean up the tracker to prevent the log file from growing very big
            $this->customer->cleanupQuotaTracker();

            $i = 0;
            $this->getPendingSubscribers($partition, function($subscribers, $page) {
                foreach ($subscribers->get() as $subscriber) {
                    // @todo: customerneedcheck
                    if ($this->customer->overQuota()) {
                        throw new \Exception("Customer has reached sending limit");
                    }

                    $i += 1;
                    MailLog::info("Sending to subscriber `{$subscriber->email}` ({$i}/".sizeof($subscribers).')');

                    // Pick up an available sending server
                    // Throw exception in case no server available
                    $server = $this->pickSendingServer($this);

                    list($message, $msgId) = $this->prepareEmail($subscriber, $server);

                    // randomly check for campaign PAUSED status after 10 emails
                    // @todo performance concern here
                    // @todo need an exclusive lock, what if the campaign is PAUSED, then RESTARTED
                    //       after the status check and before actually sending?
                    if ($this->refreshStatus()->isPaused()) {
                        event(new \Acelle\Events\CampaignUpdated($this, false));
                        throw new CampaignPausedException();
                    }

                    $sent = $server->send($message);

                    // additional log
                    MailLog::info("Sent to subscriber `{$subscriber->email}`");

                    $this->trackMessage($sent, $subscriber, $server, $msgId);
                }
            });

            // only mark campaign as done when running as its own process
            // as a child process, just finish and leave the parent process to update campaign status
            if (!$asChildProcess) {
                $this->done();
            }

        } catch (CampaignPausedException $e) {
            // just finish
            MailLog::warning('Campaign paused');
        } catch (CampaignErrorException $e) {
            // just finish
            MailLog::warning('Campaign terminated: ' . $e->getMessage());
        } catch (\Exception $e) {
            MailLog::error($e->getMessage());
            $this->error($e->getMessage());
        } finally {
            // reset server pools: just in case DeliveryServerAmazonSesWebApi
            // --> setup SNS requires using from_email of the corresponding campaign
            // but SNS is only made once when the server is initiated
            // UPDATE: this is no longer the case as one process now only deals with one campaign
            //         however, the method is still needed to save sending servers''
            MailList::resetServerPools();

            // store customer quota usage
            $this->customer->saveQuotaUsageInfo();

            // Trigger the CampaignUpdate event to update the campaign cache information
            // The second parameter of the constructor function is false, meanining immediate update
            event(new \Acelle\Events\CampaignUpdated($this, false));
            event(new \Acelle\Events\MailListUpdated($this, false));
        }
    }

    /**
     * Transform Tags
     * Transform tags to actual values before sending.
     */
    public function tagMessage($message, $subscriber, $msgId)
    {
        // @todo consider a solution for UNSUBSCRIBE_URL for test subscriber (also for other tags like: UPDATE_PROFILE_URL)

        $tags = array(
            'SUBSCRIBER_EMAIL' => $subscriber->email,
            'CAMPAIGN_NAME' => $this->name,
            'CAMPAIGN_UID' => $this->uid,
            'CAMPAIGN_SUBJECT' => $this->subject,
            'CAMPAIGN_FROM_EMAIL' => $this->from_email,
            'CAMPAIGN_FROM_NAME' => $this->from_name,
            'CAMPAIGN_REPLY_TO' => $this->reply_to,
            'SUBSCRIBER_UID' => $subscriber->uid,
            'CURRENT_YEAR' => date('Y'),
            'CURRENT_MONTH' => date('m'),
            'CURRENT_DAY' => date('d'),
            'UNSUBSCRIBE_URL' => str_replace('MESSAGE_ID', StringHelper::base64UrlEncode($msgId), Setting::get('url_unsubscribe')),
            'CONTACT_NAME' => $this->defaultMailList->contact->company,
            'CONTACT_COUNTRY' => $this->defaultMailList->contact->country->name,
            'CONTACT_STATE' => $this->defaultMailList->contact->state,
            'CONTACT_CITY' => $this->defaultMailList->contact->city,
            'CONTACT_ADDRESS_1' => $this->defaultMailList->contact->address_1,
            'CONTACT_ADDRESS_2' => $this->defaultMailList->contact->address_2,
            'CONTACT_PHONE' => $this->defaultMailList->contact->phone,
            'CONTACT_URL' => $this->defaultMailList->contact->url,
            'CONTACT_EMAIL' => $this->defaultMailList->contact->email,
            'LIST_NAME' => $this->defaultMailList->name,
            'LIST_SUBJECT' => $this->defaultMailList->default_subject,
            'LIST_FROM_NAME' => $this->defaultMailList->from_name,
            'LIST_FROM_EMAIL' => $this->defaultMailList->from_email,
            'WEB_VIEW_URL' => str_replace('MESSAGE_ID', StringHelper::base64UrlEncode($msgId), Setting::get('url_web_view')),
        );

        // UPDATE_PROFILE_URL
        if(!$this->isStdClassSubscriber($subscriber)) {
            // in case of actually sending campaign
            $tags['UPDATE_PROFILE_URL'] = str_replace('LIST_UID', $this->defaultMailList->uid,
                str_replace('SUBSCRIBER_UID', $subscriber->uid,
                str_replace('SECURE_CODE', $subscriber->getSecurityToken('update-profile'), Setting::get('url_update_profile'))));
        }

        // Update tags layout
        foreach ($tags as $tag => $value) {
            $message = str_replace('{'.$tag.'}', $value, $message);
        }

        if (!$this->isStdClassSubscriber($subscriber)) {
            // in case of actually sending campaign
            foreach ($this->defaultMailList->fields as $field) {
                $message = str_replace('{SUBSCRIBER_'.$field->tag.'}', $subscriber->getValueByField($field), $message);
            }
        } else {
            // in case of sending test email
            // @todo how to manage such tags?
            $message = str_replace('{SUBSCRIBER_EMAIL}', $subscriber->email, $message);
        }

        return $message;
    }

    /**
     * Get Pending Subscribers
     * Select only subscribers that are ready for sending. Those whose status is `blacklisted`, `pending` or `unconfirmed` are not included.
     */
    public function getPendingSubscribers($partition = null, $callback)
    {
        /* *
         *
         * Using LEFT JOIN is much slower than NOT IN
         *
        $subscribers = $this->subscribers()
                ->leftJoin(DB::raw('(SELECT * FROM '.DB::getTablePrefix().'tracking_logs WHERE campaign_id = '.$this->id.') log'), 'subscribers.id', '=', 'subscriber_id')
                ->whereRaw('log.id IS NULL')
                ->whereRaw(DB::getTablePrefix()."subscribers.status = '".Subscriber::STATUS_SUBSCRIBED."'")
                ->select('subscribers.*')
                ->get();
        */

        $subscribers = $this->subscribers([], [])
                ->whereRaw(sprintf(table('subscribers').'.id NOT IN (SELECT subscriber_id FROM %s WHERE campaign_id = %s)', table('tracking_logs'), $this->id))
                ->whereRaw(DB::getTablePrefix()."subscribers.status = '".Subscriber::STATUS_SUBSCRIBED."'")
                ->select('subscribers.email')
                ->addSelect('subscribers.uid')
                ->addSelect('subscribers.id');

        if (!is_null($partition)) {
            // retrieve the subscribers list for this partition only
            // partitioning is based on email to prevent one email from being taken by more than one process
            // The idea is to generate a unique integer for an email address, then partition base on the number
            list($partitionId, $count) = $partition;
            $subscribers = $subscribers->whereRaw(sprintf('CONV(SUBSTR(MD5(%s),1, 4), 16, 10) MOD %s = %s', table('subscribers.email'), $count, $partitionId));
        }

        // the array_unique_by functions out-perform the Collection::unique of Laravel!!!
        // The following code snipet is no longer needed as the list is already unique by email
        //     $unique = array_unique_by($subscribers, function ($r) {
        //         return $r['email'];
        //     });

        paginate($subscribers, function ($subset, $page) use ($callback) {
            $callback($subset, $page);
        });
    }

    /**
     * Prepare the email content using Swift Mailer.
     *
     * @input object subscriber
     * @input object sending server
     *
     * @return MIME text message
     */
    public function prepareEmail($subscriber, $server)
    {
        // build the message
        $customHeaders = $this->getCustomHeaders($subscriber, $this);
        $msgId = $customHeaders['X-Acelle-Message-Id'];

        $message = \Swift_Message::newInstance();
        $message->setId($msgId);

        if ($this->type == self::TYPE_REGULAR) {
            $message->setContentType('text/html; charset=utf-8');
        } else {
            $message->setContentType('text/plain; charset=utf-8');
        }

        foreach($customHeaders as $key => $value) {
            $message->getHeaders()->addTextHeader($key, $value);
        }

        // @TODO for AWS, setting returnPath requires verified domain or email address
        if ($server->allowCustomReturnPath()) {
            $returnPath = $server->getVerp($subscriber->email);
            if ($returnPath) {
                $message->setReturnPath($returnPath);
            }
        }
        $message->setSubject($this->getSubject($subscriber, $msgId));
        $message->setFrom(array($this->from_email => $this->from_name));
        $message->setTo($subscriber->email);
        $message->setReplyTo($this->reply_to);
        $message->setEncoder(\Swift_Encoding::get8bitEncoding());
        $message->addPart($this->getPlainContent($subscriber, $msgId), 'text/plain');
        if ($this->type == self::TYPE_REGULAR) {
            $message->addPart($this->getHtmlContent($subscriber, $msgId), 'text/html');
        }

        if ($this->sign_dkim) {
            $message = $this->sign($message);
        }

        // @todo attachment
        //$message->attach(Swift_Attachment::fromPath('/tmp/gaugau.csv'));
        return array($message, $msgId);
    }

    /**
     * Queue campaign for sending
     *
     */
    public function queue($trigger = null, $subscribers = null, $delay = 0)
    {
        $this->ready();
        $job = (new \Acelle\Jobs\RunCampaignJob($this))->delay($this->getDelayInSeconds());
        dispatch($job);
    }

    /**
     * Queue campaign for sending
     *
     */
    public function clearAllJobs()
    {
        // cleanup jobs and system_jobs
        // @todo data should be a JSON field instead
        $systemJobs = SystemJob::where('name', 'Acelle\Jobs\RunCampaignJob')->where('data', $this->id)->get();
        foreach($systemJobs as $systemJob) {
            // @todo what if jobs were already started? check `reserved` field?
            $systemJob->clear();
        }
    }

    /**
     * Pick up a delivery server for the campaign.
     *
     * @return mixed
     */
    public function pickSendingServer()
    {
        return $this->defaultMailList->pickSendingServer();
    }
}
