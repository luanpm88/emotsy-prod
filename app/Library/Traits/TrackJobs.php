<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Acelle\Model\JobMonitor;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use Exception;
use DB;

trait TrackJobs
{
    // DO NOT USE DB TRANSACTION
    // OTHERWISE BATCH_ID OR JOB_ID MAY NOT BE AVAILABLE
    public function dispatchWithMonitor($job)
    {
        $jobType = get_class($job);
        $monitor = JobMonitor::makeInstance($this, $jobType); // QUEUED status

        // actually save
        $monitor->save();
        $job->setMonitor($monitor);

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

        // Execute job's callback
        foreach($events as $closure) {
            if (!is_null($closure)) {
                $closure($job, $monitor);
            }
        }

        // Return
        return $monitor;
    }

    public function dispatchBatch($batch, $jobType)
    {
        // Actually dispatch
        // It is weird that $batch->distpatch() cannot be called here
        // resulting in PendingBatch with null batch->id
        //
        // $batch->dispatch();

        $monitor = JobMonitor::makeInstance($this, $jobType);
        // Job monitor for batches does not have status updated, set it to null to avoid confusing
        $monitor->status = null;

        // actually save
        // Risk: job_monitor without batch id
        // Associate job ID with monitor
        // Notice that $batch->id is only available after dispatch()
        // Also, do not place this in a DB transaction
        $monitor->batch_id = $batch->id;
        $monitor->save();

        // Return
        return $monitor;
    }

    // IMPORTANT: this is normally for jobs that create other jobs
    public function dispatchWithBatchMonitor($job, $jobType, $thenCallback, $catchCallback, $finallyCallback)
    {
        // IMPORTANT: although $monitor is not used, BUT do not setMointior for $job
        // Otherwise, it will be unexpected set as FAILED / DONE / RUNNING... by Queue events
        // while it should not (it will actually launch other jobs, the status reflected by all jobs, not this only one)
        if (!property_exists($job, 'monitor')) {
            // $eventAfterDispatched is required
            throw new Exception('Job class should use `Trackable` trait');
        }

        // Create job monitor record
        $monitor = JobMonitor::makeInstance($this, $jobType);
        $monitor->save();

        // Set job monitor
        $job->setMonitor($monitor);

        // Store the closures (for executing after dispatched) to a temporary place
        // It is because Jobs are not allowed to store closures (not serializable)
        $events = [ 
            $job->eventAfterDispatched
        ];

        // Destroy closure attributes which cannot be serialized
        $job->eventAfterDispatched = null; // Destroy the closure

        $batch = Bus::batch($job)->then(function (Batch $batch) use ($monitor, $thenCallback) {
            // Finish successfully
            $monitor->setDone();

            if (!is_null($thenCallback)) {
                $thenCallback($batch);
            }

        })->catch(function (Batch $batch, \Throwable $e) use ($monitor, $catchCallback) {
            // Failed and finish
            $monitor->setFailed($e);

            if (!is_null($catchCallback)) {
                $catchCallback($batch);
            }

        })->finally(function (Batch $batch) use ($monitor, $finallyCallback) {
            if (!is_null($finallyCallback)) {
                $finallyCallback($batch);
            }

        })->dispatch();

        $monitor->batch_id = $batch->id;
        $monitor->save();

        // Execute job's callback
        foreach($events as $closure) {
            if (!is_null($closure)) {
                $closure($job, $monitor);
            }
        }

        // Return
        return $monitor;
    }
}
