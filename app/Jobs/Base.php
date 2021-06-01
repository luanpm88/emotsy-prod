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
        $systemJob = new SystemJobModel(array(
            'name' => get_called_class(),
            'object_id' => $objectId,
            'object_name' => $objectName,
        ));

        $systemJob->setNew(); // also save!
        
        $this->systemJobId = $systemJob->id;
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
}
