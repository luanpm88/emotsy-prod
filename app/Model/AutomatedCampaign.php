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

class AutomatedCampaign extends Campaign
{
    protected $table = 'campaigns';

    /**
     * Start the auto campaign
     * 
     */
    public function start($auto_trigger_id = NULL, $subscribers = []) { // @todo NULL or empty array???
        try {
            MailLog::info('Starting auto campaign `'.$this->name.'`');

            // Reset max_execution_time so that command can run for a long time without being terminated
            self::resetMaxExecutionTime();

            // @todo now only run as single process
            $this->run($auto_trigger_id, $subscribers);

            MailLog::info('Finish campaign `'.$this->name.'`');
        } catch (\Exception $ex) {
            // Set ERROR status
            $this->error($ex->getMessage());
            MailLog::error('Campaign failed. '.$ex->getMessage());
        }
    }

    public function run($auto_trigger_id, $subscribers)
    {
        try {
            $i = 0;
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
                    'to' => $subscriber->email, // workaround: some sending engine requires the TO explicitly (not in MIME message)
                    'campaign' => $this,
                    'subscriber' => $subscriber,
                    'msgId' => $msgId
                ));
                
                // track the auto trigger id
                $response['auto_trigger_id'] = $auto_trigger_id;

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
    public function queue($delay = 0)
    {
        $job = (new \Acelle\Jobs\RunAutomatedCampaignJob($this))->delay($delay);
        dispatch($job);
    }
}