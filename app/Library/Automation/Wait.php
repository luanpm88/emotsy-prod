<?php

namespace Acelle\Library\Automation;

class Wait extends Action
{
    public function execute($trigger)
    {
        $this->recordLastExecutedTime();

        return true;
    }

    public function getTimelineDescription()
    {
        return 'Waiting for the event to occur';
    }
}
