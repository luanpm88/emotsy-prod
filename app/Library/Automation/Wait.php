<?php

namespace Acelle\Library\Automation;

class Wait extends Action
{
    public function execute()
    {
        $this->recordLastExecutedTime();

        return true;
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();
        return sprintf('Wait for 24 hours before proceeding with the next event for user %s', $nameOrEmail);
    }
}
