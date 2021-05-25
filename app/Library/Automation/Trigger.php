<?php

namespace Acelle\Library\Automation;

class Trigger extends Action
{
    public function execute($callback = null)
    {
        $this->recordLastExecutedTime();
        
        if (!is_null($callback)) {
            $callback($this);
        }

        return true;
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('User %s subscribes to mail list, automation triggered!', $nameOrEmail);
    }
}
