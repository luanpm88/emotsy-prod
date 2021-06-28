<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class JobMonitor extends Model
{
    use HasFactory;

    public function getBatch()
    {
        if (is_null($this->batch_id)) {
            return;
        }

        return Bus::findBatch($this->batch_id);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function getJob()
    {
        return $this->job;
    }

    public function hasJob()
    {
        return ! is_null($this->job_id);
    }

    public function hasBatch()
    {
        return ! is_null($this->batch_id);
    }

    public function scopeJob($query)
    {
        return $query->whereNotNull('job_id');
    }

    public function scopeBatch($query)
    {
        return $query->whereNotNull('batch_id');
    }

    public static function forBatch($subject, Batch $batch, $jobType)
    {
        $monitor = new self();
        $monitor->subject_name = get_class($subject);
        $monitor->subject_id = $subject->id;
        $monitor->batch_id = $batch->id;
        $monitor->job_type = $jobType;
        $monitor->save();

        return $monitor;
    }

    public static function forJob($subject, $job_id, $jobType)
    {
        $monitor = new self();
        $monitor->subject_name = get_class($subject);
        $monitor->subject_id = $subject->id;
        $monitor->job_id = $job_id;
        $monitor->job_type = $jobType;
        $monitor->save();

        return $monitor;
    }
}
