<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Acelle\Jobs\DeliverEmail;

class Send extends Action
{
    public function execute($autoTrigger)
    {
        $email = Email::findByUid($this->options['email_uid']);
        dispatch(new DeliverEmail($email, $autoTrigger->subscriber));
        $autoTrigger->logger()->info(sprintf('Send email entitled "%s" to "%s", queued', $email->subject, $autoTrigger->subscriber->email));

        $this->recordLastExecutedTime();

        return true;
    }
}
