<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Acelle\Jobs\DeliverEmail;

class Send extends Action
{
    public $email_id;

    public function __construct($params = [])
    {
        // title: "Send a Welcome email", id: "welcome", type: "ElementAction", child: "condition"
        parent::__construct($params);

        $this->email_id = array_key_exists('email_id', $params) ? $params['email_id'] : null;
    }

    public function toJson()
    {
        $json = parent::toJson();
        $json = array_merge($json, [
            'email_id' => $this->email_id,
        ]);

        return $json;
    }

    public function execute($autoTrigger)
    {
        $email = Email::find($this->email_id);
        dispatch(new DeliverEmail($email, $autoTrigger->subscriber));

        $this->recordLastExecutedTime();

        return true;
    }
}
