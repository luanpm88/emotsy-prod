<?php

namespace Acelle\Library\Automation;

use Carbon\Carbon;

class Send extends Action
{
    function __construct($params = [])
    {
        // title: "Send a Welcome email", id: "welcome", type: "ElementAction", child: "condition"
        parent::__construct($params);

        $this->email_id = array_key_exists('email_id', $params) ? $params['email_id'] : null;
    }

    public function execute()
    {
        $this->setOption('checked_at', Carbon::now()->timestamp);
        return true;
    }
}
