<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Acelle\Model\Subscriber;
use Acelle\Library\QuotaManager;
use Acelle\Library\Exception\NoCreditsLeft;
use Acelle\Library\Exception\QuotaExceeded;
use Exception;

class SendMessage implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    public $maxExceptions = 1; // This is required if retryUntil is used, otherwise, the default value is 255
    public $failOnTimeout = true;
    public $tries = 1;

    protected $subscriber;
    protected $server;
    protected $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, Subscriber $subscriber, $server)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
        $this->server = $server;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addHours(12);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $this->send();
    }

    // Use a dedicated method with no dependency for easy testing
    public function send($exceptionCallback = null)
    {
        $logger = $this->campaign->logger();
        $email = $this->subscriber->getEmail();
        $subscription = $this->campaign->customer->activeSubscription();

        // Prepare the email message to send
        list($message, $msgId) = $this->campaign->prepareEmail($this->subscriber, $this->server);

        // Actually send!
        $logger->info(sprintf('Sending to %s [Server "%s"]', $email, $this->server->name));

        try {
            // Count related quota trackers
            QuotaManager::with($subscription, 'send')->enforce();
            QuotaManager::with($this->server, 'send')->enforce();

            // Actually send (or throw an exception)
            $sent = $this->server->send($message);

            // Log successful shot
            $this->campaign->trackMessage($sent, $this->subscriber, $this->server, $msgId);
            $logger->info(sprintf('Sent to %s [Server "%s"]', $email, $this->server->name));
        } catch (QuotaExceeded $ex) {
            if (!is_null($exceptionCallback)) {
                $exceptionCallback($ex);
            }
            // Releease the job, have it tried again later on, after 1 minutes
            $logger->warning(sprintf("Sending to [%s] -> put on hold (for 60 seconds): %s", $email, $ex->getMessage()));

            // Release the job, have it try again after 60 seconds and (hopefully) the quota limits will be lifted then as time goes by
            $this->release(60);

        } catch (Exception $ex) {
            if (!is_null($exceptionCallback)) {
                $exceptionCallback($ex);
            }
            $message = sprintf("Error sending to email address [%s]. Error: %s", $email, $ex->getMessage());
            $logger->error($message);
            throw new Exception($message);
        }
    }
}
