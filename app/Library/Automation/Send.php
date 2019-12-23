<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Acelle\Jobs\DeliverEmail;

class Send extends Action
{
    public function execute()
    {
        $email = $this->getEmail();
        dispatch(new DeliverEmail($email, $this->autoTrigger->subscriber));
        $this->autoTrigger->logger()->info(sprintf('Send email entitled "%s" to "%s", queued', $email->subject, $this->autoTrigger->subscriber->email));

        $this->recordLastExecutedTime();

        return true;
    }

    public function getEmail()
    {
        return Email::findByUid($this->options['email_uid']);
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();
        return sprintf('User %s receives email entitled "%s"', $nameOrEmail, $this->getEmail()->subject);
    }
}
