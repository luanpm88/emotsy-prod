<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Exception;
use Acelle\Model\JobMonitor;
use DB;

trait TrackJobs
{
    public function dispatchWithMonitor($job)
    {
        $monitor = new JobMonitor();
        $monitor->subject_name = get_class($this);
        $monitor->subject_id = $this->id;
        $monitor->job_type = get_class($job);
        $monitor->status = JobMonitor::STATUS_QUEUED;

        DB::transaction(function() use ($job, $monitor) {
            // actually save
            $monitor->save();
            $job->setMonitor($monitor);

            // Event
            if (!is_null($job->eventBeforeDispatched)) {
                $callback = $job->eventBeforeDispatched;
                $callback($job, $monitor);
            }

            // Store the closures (for executing after dispatched) to a temporary place
            // It is because Jobs are not allowed to store closures (not serializable)
            $events = [ 
                $job->eventAfterDispatched
            ];

            // Destroy closure attributes which cannot be serialized
            $job->eventAfterDispatched = null; // Destroy the closure

            // Actually dispatch
            $dispatchedJobId = app(Dispatcher::class)->dispatch($job);

            // Associate job ID with monitor
            $monitor->job_id = $dispatchedJobId;
            $monitor->save();

            // Execute callback
            foreach($events as $closure) {
                $closure($job, $monitor);
            }
        });

        // Return
        return $monitor;
    }
}
