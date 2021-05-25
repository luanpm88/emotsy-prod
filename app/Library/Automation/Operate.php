<?php

namespace Acelle\Library\Automation;

class Operate extends Action
{
    /*****

        Operate action may result in the following cases:
          + Done OK
          + Exception (any type of exception...)
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Execution always returns TRUE

    ****/
    public function execute($callback = null)
    {
        // IMPORTANT
        // If this is the latest also the last action of the workflow
        // no more execute, just return true
        if (!is_null($this->last_executed)) {
            throw new Exception("Action already executed");
        }

        if (config('app.demo') == true) {
            // Do nothing here
        } else {
            // Do something here
            sleep(1);
        }

        $this->autoTrigger->logger()->info(sprintf('Perform an action...'));

        $this->recordLastExecutedTime();

        if (!is_null($callback)) {
            $callback($this);
        }

        return true;
    }

    // Overwrite
    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('Perform an operation');
    }
}
