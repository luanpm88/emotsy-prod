<?php

namespace Acelle\Library\Automation;

class Trigger extends Action
{
    protected function doExecute($manually)
    {
        return true;
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('User %s subscribes to mail list, automation triggered!', $nameOrEmail);
    }

    public function getProgressDescription()
    {
        if (is_null($this->getLastExecuted())) {
            return "* Triggering automation";
        } else {
            return "Automation triggered";
        }
    }

    public function isDelayAction()
    {
        return true;
    }
}
