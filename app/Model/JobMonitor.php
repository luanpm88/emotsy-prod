<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class JobMonitor extends Model
{
    use HasFactory;

    const STATUS_NEW = 'new';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    public function getBatch()
    {
        return Bus::findBatch($this->batch_id);
    }

    public static function launch($subject, Batch $batch)
    {
        $monitor = new self();
        $monitor->subject_name = get_class($subject);
        $monitor->subject_id = $subject->id;
        $monitor->batch_id = $batch->id;
        $monitor->save();

        return $monitor;
    }
}
