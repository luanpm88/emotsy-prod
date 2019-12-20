<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Acelle\Jobs\DeliverEmail;

class Send extends Action
{
    public function execute($autoTrigger)
    {
        $email = $this->getEmail();
        dispatch(new DeliverEmail($email, $autoTrigger->subscriber));
        $autoTrigger->logger()->info(sprintf('Send email entitled "%s" to "%s", queued', $email->subject, $autoTrigger->subscriber->email));

        $this->recordLastExecutedTime();

        return true;
    }

    public function getEmail()
    {
        return Email::findByUid($this->options['email_uid']);
    }

    public function getTimelineDescription()
    {
        return sprintf('Receive email entitled "%s"', $this->getEmail()->subject);
    }
}
