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
        } catch (Exception $e) {
            // Email is always present
            // Do not throw exception here, in case of invalid email address
            $this->logger->error('['.$this->attributes['email']. '] failed to import. '.$e->getMessage());
            return;
        }

        // Verify email address against remote service
        $verifier = $this->list->customer->getEmailVerificationServers()->first();

        if (is_null($verifier)) {
            throw new Exception('No email verification service found');
        }

        $verifa = $subscriber->verify($verifier);
        if ($verifa->isDeliverable() || $verifa->isUnknown()) {
            $this->logger->info(sprintf('[%s] successfully imported [checked by %s]', $subscriber->email, $verifier->name));
        } else {
            // In case of failure, delete the newly created contact
            // Throw exception to log
            $subscriber->delete();
            $this->logger->error(sprintf('[%s] failed to import. Undeliverable email [Result: `%s`, checked by `%s`]', $subscriber->email, $verifa->result, $verifier->name));
        }
    }
}
