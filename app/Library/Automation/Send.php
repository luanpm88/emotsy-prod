<?php

namespace Acelle\Library\Automation;

class Send extends Action
{
    function __construct($params = [])
    {
        // title: "Send a Welcome email", id: "welcome", type: "ElementAction", child: "condition"
        parent::__construct($params);

        $this->email_id = array_key_exists('email_id', $params) ? $params['email_id'] : null;
    }
}
