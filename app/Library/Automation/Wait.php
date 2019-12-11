<?php

namespace Acelle\Library\Automation;

class Wait extends Action
{
    public function execute()
    {
        $this->recordLastExecutedTime();

        return false;
    }
}
