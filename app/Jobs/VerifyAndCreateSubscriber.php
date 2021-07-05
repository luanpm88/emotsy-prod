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

        if (rand(0, 3) == 1) {
            $this->logger->warning('Tak tak tak: ' . $this->attributes['email']);
        }

        $this->logger->warning('Okie okie okie: ' . $this->attributes['email']);

        return $this->list->addSubscriberFromArray($this->attributes);
    }
}
