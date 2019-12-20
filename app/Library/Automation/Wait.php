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
        return 'Wait for 24 hours before proceeding with the next event';
    }
}
