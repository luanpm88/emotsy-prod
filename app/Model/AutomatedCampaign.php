<?php

/**
 * Automated Campaign Class
 *
 * Model class for automated campaigns.
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
use Carbon\Carbon;
use Acelle\Model\Campaign;
use Acelle\Jobs\RunAutomatedCampaignJob;
use Acelle\Library\Log as MailLog;

class AutomatedCampaign extends Campaign
{
    protected $table = 'campaigns';
    protected $trigger = null;

    /**
     * Start the auto campaign
     * 
     */
    public function start($trigger = null, $subscribers = null) {
        if (is_null($trigger)) {
            throw new \Exception("Trigger must not be null for Automated Campaign");
        }
        
        // set the trigger
        $this->trigger = $trigger;

        try {
            MailLog::info('Start auto campaign `'.$this->name.'`, trigger ID: ' . $this->trigger->id);

            // Reset max_execution_time so that command can run for a long time without being terminated
            self::resetMaxExecutionTime();

            // @todo now only run as single process
            $this->run($subscribers);

            MailLog::info('Finish auto campaign `'.$this->name.'`');
        } catch (\Exception $ex) {
            // Set ERROR status
            $this->error($ex->getMessage());
            MailLog::error('Auto campaign failed. '.$ex->getMessage());
        }
    }

    public function run($subscribers = null)
    {
        try {
            $i = 0;
            
            if (is_null($subscribers)) {
                $subscribers = $this->trigger->autoEvent->automation->subscribers()->get();
            }
            foreach ($subscribers as $subscriber) {
                if ($this->user->overQuota()) {
                    throw new \Exception("Customer (ID: {$this->user->id}) has reached sending quota");
                }

                $i += 1;
                MailLog::info("Sending to subscriber `{$subscriber->email}` ({$i}/".sizeof($subscribers).')');

                // Pick up an available sending server
                // Throw exception in case no server available
                $server = $this->pickServer($this);

                list($message, $msgId) = $this->prepareEmail($subscriber, $server);

                $response = $server->send($message, array(
                    'from_email' => $this->from_email,
                    'to' => $subscriber->email, // workaround: some sending engine requires the TO explicitly as parameter (not in MIME message)
                    'campaign' => $this,
                    'subscriber' => $subscriber,
                    'msgId' => $msgId
                ));
                
                // track the auto trigger id
                $response['auto_trigger_id'] = $this->trigger->id;

                // record the finish datetime of the campaign triggered
                $this->trigger->setFinished();
                
                // record to tracking log
                $this->trackMessage($response, $subscriber, $server, $msgId);
            }
            
            // mark the campaign as done
            $this->done();
            
            // record quota usage
            $this->user->saveQuotaUsageInfo();
        } catch (\Exception $e) {
            MailLog::error($e->getMessage());
            $this->error($e->getMessage());
        } finally {
            // reset server pools: just in case DeliveryServerAmazonSesWebApi
            // --> setup SNS requires using from_email of the corresponding campaign
            // but SNS is only made once when the server is initiated
            //     SendingServer::resetServerPools();
        }
    }

    /**
     * Queue campaign for sending
     *
     */
    public function queue($trigger = null, $subscribers = null, $delay = 0)
    {
        // @note only set the trigger when start()
        // since campaign can be started without queue()
        MailLog::info('Automated campaign queued `' . $this->name . '`, trigger ID: ' . $trigger->id );
        $job = (new RunAutomatedCampaignJob($this, $trigger, $subscribers))->delay($delay);
        dispatch($job);
    }
}