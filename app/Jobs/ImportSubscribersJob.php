<?php

namespace Acelle\Jobs;

use Acelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSubscribersJob extends ImportExportJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailList, $user, $file)
    {
        // call parent's constructor
        parent::__construct($mailList, $user);
        
        // Upload csv
        $filename = 'data.csv';
        $file->move($this->path, $filename);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $job = $this;
        \Acelle\Model\MailList::import($job->mailList, $job->user, $job);
    }
}
