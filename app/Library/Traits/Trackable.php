<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Exception;
use Acelle\Model\JobMonitor;

trait Trackable
{
    public $monitor;

    public function setMonitor(JobMonitor $monitor)
    {
        $this->monitor = $monitor;
    }
}
