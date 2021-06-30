<?php

namespace Acelle\Jobs;

use Acelle\Library\Traits\Trackable;

class ImportSubscribersJob extends Base
{
    use Trackable;

    // @todo this should better be a constant
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

        // Set the initial value for progress check
        $this->afterDispatched(function($thisJob, $monitor) {
            $monitor->setJsonData([
                'percentage' => 0,
                'total' => 0,
                'processed' => 0,
                'failed' => 0,
                'message' => 'Import is being queued for processing...',
            ]);
        });
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // File path: 
        $this->list->import($this->file, function($processed, $total, $failed, $message) {
            $percentage = ($total && $processed) ? (int)($processed*100/$total) : 0;

            $this->monitor->updateJsonData([
                'percentage' => $percentage,
                'total' => $total,
                'processed' => $processed,
                'failed' => $failed,
                'message' => $message,
            ]);
        });
    }
}
