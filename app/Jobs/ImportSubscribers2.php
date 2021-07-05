<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Batchable;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Exception;
use Acelle\Library\Traits\Trackable;

class ImportSubscribers2 extends Base
{
    use Batchable, Trackable;

    protected $list;
    protected $file; // Example: /home/acelle/storage/app/tmp/import-000000.csv

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($list, $file)
    {
        $this->list = $list;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Use a logger to log failed 
        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\n");
        $logfile = $this->file.".log";
        $stream = new StreamHandler($logfile, Logger::DEBUG);
        $stream->setFormatter($formatter);

        $pid = getmypid();
        $logger = new Logger($pid);
        $logger->pushHandler($stream);

        $this->monitor->updateJsonData([
            'logfile' => $logfile,
        ]);

        // Write log, to make sure the file is created
        $logger->info('Initiated');

        $this->list->parseCsvFile($this->file, function($record) use ($logger) {
            $this->batch()->add( new VerifyAndCreateSubscriber($this->list, $record, $logger) );
        });
    }
}
