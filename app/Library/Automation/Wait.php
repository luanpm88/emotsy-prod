<?php

namespace Acelle\Library\Automation;

use Carbon\Carbon;

class Wait extends Action
{
    public function execute()
    {
        sleep(1); // to avoid same day with previous action when modifying (n days)
        $now = Carbon::now();
        $waitDuration = $this->getOption('time');  // 1 hour, 1 day, 2 days
        $parentExecutionTime = $carbon = Carbon::createFromTimestamp($this->getParent()->getLastExecuted());
        var_dump($parentExecutionTime->toString());
        $due = $parentExecutionTime->modify($waitDuration);

        $checked = $now->gte($due);

        $this->recordLastExecutedTime();

        if ($checked) {
            $this->autoTrigger->logger()->info(sprintf('---> It is already %s minutes (or %s hours) due! move to next action!', $now->diffInMinutes($due), $now->diffInHours($due)));
        } else {
            $this->autoTrigger->logger()->info(sprintf('---> Waiting for another %s minutes (or %s hours)...', $now->diffInMinutes($due), $now->diffInHours($due)));
        }

        return $checked;
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();
        return sprintf('Wait for 24 hours before proceeding with the next event for user %s', $nameOrEmail);
    }
}
