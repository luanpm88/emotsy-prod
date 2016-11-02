<?php

namespace Acelle\Jobs;

use Acelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportSubscribersJob extends ImportExportJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $job = $this;
        \Acelle\Model\MailList::export($job->mailList, $job->user, $job);
    }
}
