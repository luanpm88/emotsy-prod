<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Exception;
use Acelle\Model\JobMonitor;

trait TrackJobs
{
    public function dispatchWithMonitor($job)
    {
        $monitor = new JobMonitor();
        $monitor->subject_name = get_class($this);
        $monitor->subject_id = $this->id;
        $monitor->job_type = get_class($job);
        $monitor->save();
        $job->setMonitor($monitor);

        // Actually dispatch
        $dispatchedJobId = app(Dispatcher::class)->dispatch($job);

        // Associate job ID with monitor
        $monitor->job_id = $dispatchedJobId;
        $monitor->status = JobMonitor::STATUS_QUEUED;
        $monitor->save();
        return $monitor;
    }
}
