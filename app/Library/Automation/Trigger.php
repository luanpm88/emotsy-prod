<?php

namespace Acelle\Library\Automation;

class Trigger extends Action
{
    public function execute($autoTrigger)
    {
        $this->recordLastExecutedTime();
        return true;
    }

    public function getTimelineDescription()
    {
        return sprintf('Subscribe to mail list, automation triggered!');
    }
}