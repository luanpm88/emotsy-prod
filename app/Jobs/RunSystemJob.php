<?php

namespace Acelle\Jobs;

use Acelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunSystemJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $system_job;

    /**
     * Create a new job instance.
     */
    public function __construct(\Acelle\Model\SystemJob $system_job)
    {
        $this->system_job = $system_job;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->system_job->run();
    }
}
