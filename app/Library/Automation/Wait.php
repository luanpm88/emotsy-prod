<?php

namespace Acelle\Library\Automation;

use Carbon\Carbon;

class Wait extends Action
{
    /*****

        Wait action may result in the following cases:
          + True - pass, go to next step
          + False - not pass, wait...
          + Exception - for any reason
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Return FALSE or TRUE (update last_executed before returning true)

    ****/

    public function execute($callback = null)
    {
        if (config('app.demo') == true) {
            $check = (bool) random_int(0, 1);
            
            if ($check) {
                $this->recordLastExecutedTime();

                if (!is_null($callback)) {
                    $callback($this);
                }
            }
            return $check;
        }

        $now = Carbon::now();
        $waitDuration = $this->getOption('time');  // 1 hour, 1 day, 2 days
        $parentExecutionTime = Carbon::createFromTimestamp($this->getParent()->getLastExecuted());
        $due = $parentExecutionTime->modify($waitDuration);

        $check = $now->gte($due);

        if ($check) {
            sleep(1); // to avoid same day with previous action when modifying (n days)
            $this->recordLastExecutedTime();

            if (!is_null($callback)) {
                $callback($this);
            }

            $this->autoTrigger->logger()->info(sprintf('---> It is already %s minutes (or %s hours) due! move to next action!', $now->diffInMinutes($due), $now->diffInHours($due)));
        } else {
            $this->autoTrigger->logger()->info(sprintf('---> Waiting for another %s minutes (or %s hours)...', $now->diffInMinutes($due), $now->diffInHours($due)));
        }

        return $check;
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('Wait for 24 hours before proceeding with the next event for user %s', $nameOrEmail);
    }
}
