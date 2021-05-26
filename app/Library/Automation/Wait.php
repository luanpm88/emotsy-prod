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

    public function execute($callback)
    {
        // IMPORTANT
        // If this is the latest also the last action of the workflow
        // no more execute, just return true
        if (!is_null($this->last_executed)) {
            throw new Exception("Action already executed");
        }

        if (config('app.demo') == true) {
            $check = (bool) random_int(0, 1);
            
            if ($check) {
                $this->recordLastExecutedTime();

                $callback($this);

                return true;
            } else {
                return false;
            }
        }

        $now = Carbon::now();
        $waitDuration = $this->getOption('time');  // 1 hour, 1 day, 2 days
        $parentExecutionTime = Carbon::createFromTimestamp($this->getParent()->getLastExecuted());
        $due = $parentExecutionTime->modify($waitDuration);

        $check = $now->gte($due);

        if ($check) {
            sleep(1); // to avoid same day with previous action when modifying (n days)
            $this->recordLastExecutedTime();

            // IMPORTANT: if action is not saved (with last_executed flag not null), it MIGHT execute again! sending duplicate emails for example
            // Or, if wait is not saved after record last_executed, it will be recorded over and over again every time trigger#action is loaded (then last_executed is still null)
            $callback($this);

            $this->autoTrigger->logger()->info(sprintf('---> It is already %s minutes (or %s hours) due! do save me and move to next action!', $now->diffInMinutes($due), $now->diffInHours($due)));
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

    public function getProgressDescription()
    {
        if (is_null($this->getLastExecuted())) {
            return '* Wait';
        }

        return 'Hold on for '.$this->getOption('time');
    }
}
