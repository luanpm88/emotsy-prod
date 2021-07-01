<?php

namespace Acelle\Jobs;

class ExportSegmentsJob extends Base
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailList, $customer, $fields, $uid)
    {
        ///
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $job = $this;
            \Acelle\Model\MailList::exportSegments($job->mailList, $job->customer, $job);
        } catch (\Exception $e) {
            
            
        }
    }
}
