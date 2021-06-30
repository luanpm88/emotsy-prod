<?php

namespace Acelle\Library\Traits;

use Illuminate\Contracts\Bus\Dispatcher;
use Exception;
use Acelle\Model\JobMonitor;

trait Trackable
{
    public $monitor;
    public $eventBeforeDispatched;
    public $eventAfterDispatched;

    public function setMonitor(JobMonitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function beforeDispatched($callback)
    {
        $this->eventBeforeDispatched = $callback;
    }

    public function afterDispatched($callback)
    {
        $this->eventAfterDispatched = $callback;
    }
}
