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
        try {
            $job = $this;
            \Acelle\Model\MailList::import($job->mailList, $job->user, $job);
        } catch (\Exception $e) {
            $systemJob = $this->getSystemJob();
            
            // set failed
            $systemJob->data = json_encode([
                "mail_list_uid" => $job->mailList->uid,
                "status" => "failed",
                "message" => $e->getMessage(),
                "total" => 0,
                "success" => 0,
                "error" => 0,
                "percent" => 0
            ]);
            $systemJob->save();
        }
    }
}
