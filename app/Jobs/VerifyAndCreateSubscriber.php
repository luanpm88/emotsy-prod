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
    protected $jobMonitor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($list, $attributes, $logger, $jobMonitor)
    {
        $this->list = $list;
        $this->attributes = $attributes;
        $this->logger = $logger;
        $this->jobMonitor = $jobMonitor;
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
            $this->fails('['.$this->attributes['email']. '] failed to import. '.$e->getMessage());
            return;
        }

        // Verify email address against remote service
        $verifier = $this->list->customer->getEmailVerificationServers()->first();

        if (is_null($verifier)) {
            throw new Exception('No email verification service found');
        }

        $verifa = $subscriber->verify($verifier);
        if ($verifa->isDeliverable() || $verifa->isUnknown()) {
            $this->done(sprintf('[%s] successfully imported [checked by %s]', $subscriber->email, $verifier->name));
        } else {
            // In case of failure, delete the newly created contact
            // Throw exception to log
            $subscriber->delete();
            $this->fails(sprintf('[%s] failed to import. Undeliverable email [Result: `%s`, checked by `%s`]', $subscriber->email, $verifa->result, $verifier->name));
        }
    }

    public function fails($message)
    {
        // Update log
        $this->logger->error($message);

        // Update stats
        $this->updateProgress(false);
    }

    public function done($message)
    {
        // Update log
        $this->logger->info($message);

        // Update stats
        $this->updateProgress(true);
    }

    public function updateProgress($success)
    {
        $this->jobMonitor->withExclusiveLock(function($jobMonitor) use ($success) {
            // Update stats
            $progress = $jobMonitor->getJsonData();

            $failed = array_key_exists('failed', $progress) ? $progress['failed'] : 0;
            $processed = array_key_exists('processed', $progress) ? $progress['processed'] : 0;

            if ($success) {
                $processed += 1;
            } else {
                $failed += 1;
            }

            $notice = sprintf('Imported %s, failed: %s', $processed, $failed);

            $jobMonitor->updateJsonData([
                'processed' => $processed,
                'failed' => $failed,
                'message' => $notice,
            ]);
        });
    }
}
