<?php

namespace Acelle\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

use Acelle\Model\SystemJob as SystemJobModel;

class Base implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $systemJobId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($objectId = null, $objectName = null)
    {
        // init the SystemJob record to keep track of
        $systemJob = SystemJobModel::create(array(
            'status' => SystemJobModel::STATUS_NEW,
            'name' => get_called_class(),
            'object_id' => $objectId,
            'object_name' => $objectName,
        ));
        
        $this->systemJobId = $systemJob->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function getSystemJob()
    {
        return SystemJobModel::find($this->systemJobId);
        // @todo: what if job cannot be found?
    }

    /**
     * Cark the SystemJob record as RUNNING
     *
     * @return void
     */
    public function setStarted()
    {
        $systemJob = $this->getSystemJob();
        $systemJob->setStarted();
    }

    /**
     * Cark the SystemJob record as DONE
     *
     * @return void
     */
    public function setDone()
    {
        $systemJob = $this->getSystemJob();
        $systemJob->setDone();
    }

    /**
     * Mark the SystemJob record as FAILED
     *
     * @return void
     */
    public function setFailed($msg = null)
    {
        $systemJob = $this->getSystemJob();
        $systemJob->setFailed($msg);
    }
}
