<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Exception;

class VerifyAndCreateSubscriber extends Base
{
    use Batchable;

    protected $list;
    protected $attributes; // Example: /home/acelle/storage/app/tmp/import-000000.csv
    protected $logger;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($list, $attributes, $logger)
    {
        $this->list = $list;
        $this->attributes = $attributes;
        $this->logger = $logger;
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

        try {
            // Create subscriber RECORD
            // Perform simple email address validation
            $subscriber = $this->list->addSubscriberFromArray($this->attributes);

            // Verify email address against remote service
            $verifier = $this->list->customer->getEmailVerificationServers()->first();
            $isDeliverable = $subscriber->verify($verifier);
            if (!$isDeliverable) {
                // In case of failure, delete the newly created contact
                // Throw exception to log
                $subscriber->delete();
                throw new Exception(sprintf('[Checked by %s] invalid email address %s', $verifier->name, $subscriber->email));
            }

            $this->logger->info($this->attributes['email']. ' added!');
        } catch (Exception $e) {
            // email is always present
            $this->logger->error($this->attributes['email']. ' failed. '.$e->getMessage());
        }
    }
}
