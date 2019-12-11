<?php

namespace Acelle\Library\Automation;

use Carbon\Carbon;

class Wait extends Action
{
    public function execute()
    {
        $this->setOption('last_checked_at', Carbon::now()->timestamp);

        return false;
    }
}
