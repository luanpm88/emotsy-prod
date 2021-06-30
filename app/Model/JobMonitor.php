<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Exception;

class JobMonitor extends Model
{
    use HasFactory;

    const STATUS_QUEUED = 'queued';
    const STATUS_RUNNING = 'running';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

     /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($me) {
            $me->uid = uniqid();
        });
    }

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

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

    public function setFailed($exception) {
        $this->status = self::STATUS_FAILED;
        $errorMsg = "Error executing job. ".$exception->getMessage()."\n".$exception->getTraceAsString();        
        $this->error = $errorMsg;
        $this->save();
    }

    public function setRunning() {
        $this->status = self::STATUS_RUNNING;
        $this->save();
    }

    public function setDone() {
        $this->status = self::STATUS_DONE;
        $this->save();
    }

    public function setQueued() {
        $this->status = self::STATUS_QUEUED;
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_RUNNING, self::STATUS_QUEUED]);
    }

    // @TODO: for batch, store the association only, do not use any other feature of the JobMonitor
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

    public function getJsonData()
    {
        // Also convert null or empty string to an empty array ([])
        return json_decode($this->data, true) ?: [];
    }

    public function setJsonData($data)
    {
        $this->data = json_encode($data);
        $this->save();
    }

    public function updateJsonData($data)
    {
        $json = $this->getJsonData();
        $json = array_merge($json, $data);
        $this->setJsonData($json);
    }

    public function isActive()
    {
        return $this->status == self::STATUS_QUEUED || self::STATUS_RUNNING;
    }

    public function cancel()
    {
        // @TODO: more checking here: if job is actually running (with a record in jobs table --> do not allow cancel)
        if (!$this->isActive()) {
            throw new Exception('Cannot cancel job. Job status is: '.$this->status);
        }

        $job = $this->getJob();

        if ($job) {
            $job->delete();
        }

        // For now, do not store cancelled job
        $this->delete();
    }
}
