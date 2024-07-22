<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Exception;

class Send extends Action
{
    /*****

        Send action may result in the following cases:
          + Send OK (email queued, do not care about delivery status)
          + Job dispatched (return false, wait until the related job updates this action sent_at value)
          + Exception (email UID not found for example, other exception...)
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Execution always returns TRUE

    ****/

    protected function doExecute()
    {
        /*** do not care if subscriber is active() or not ***/
        /*
        if (!$subscriber->isActive()) {
            $this->logger->warning(sprintf('Subscriber "%s" is not active (current status: "%s")', $subscriber->email, $subscriber->status));

            return false;
        }
        */

        if ($this->options['init'] == 'false' || $this->options['init'] == false) {
            throw new Exception(trans('messages.automation.email.error.not_set_up'));
        }

        $email = $this->getEmail();
        $subscriber = $this->autoTrigger->subscriber;

        if (config('app.demo') == 'true') {
            // do not wait
        } else {
            // to avoid same date/time with previous wait, wrong timeline order
            sleep(1);
        }

        if (is_null($this->getOption('queued_at'))) {
            $jobId = $email->queueDeliverTo($this->autoTrigger->subscriber, $this->autoTrigger->id, $this->getId());

            $this->setOption('queued_at', now()->toString()); // Store UTC value
            $this->setOption('delivery_job_id', $jobId); // Store UTC value

            // IMPORTANT: update action BEFORE dispatching job. In case job executes so fast! it even write to sent_at field before writing to queue_at
            // Or, if the update function is wrapped in an atomic action then it is oke
            $this->autoTrigger->updateAction($this);


            $this->logger->info(sprintf('Queue email entitled "%s" to "%s", job id %s', $email->subject, $this->autoTrigger->subscriber->email, $jobId));
        } elseif (is_null($this->getOption('sent_at'))) {
            // Job dispatched, but not finish
            $this->logger->info(sprintf('Wait for email entitled "%s" to "%s", dispatched at %s', $email->subject, $this->autoTrigger->subscriber->email, $this->getOption('queued_at')));
            return false;
        } else {
            $this->logger->info(sprintf('Sent noticed! great! "%s" to "%s"', $email->subject, $this->autoTrigger->subscriber->email));
            return true;
        }
    }

    public function getEmail()
    {
        $email = Email::findByUid($this->options['email_uid']);
        if (is_null($email)) {

            $this->setOption('email_uid', null);
            $this->autoTrigger->automation2->updateAction($this);

            throw new \Exception(sprintf("Cannot find email with UID %s for Action ID %s, title: '%s', AutoTrigger: #%s", $this->options['email_uid'], $this->getId(), $this->getTitle(), $this->autoTrigger->id));
        }
        return $email;
    }

    public function fixInvalidEmailUid()
    {
        $this->setOption('email_uid', null);
        $this->setOption('init', false);
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('User %s receives email entitled "%s"', $nameOrEmail, $this->getEmail()->subject);
    }

    public function getProgressDescription()
    {
        $email = $this->getEmail();
        $subscriber = $this->autoTrigger->subscriber;

        if (!is_null($this->getLastExecuted())) {
            return sprintf('Sent email "%s" to "%s"', $email->subject, $subscriber->email);
        } elseif ($this->getOption('queued_at')) {
            $queuedAt = $this->autoTrigger->automation2->customer->parseDateTime($this->getOption('queued_at'));
            return sprintf('Sending email "%s" to "%s". Job #%s queued %s at %s', $email->subject, $subscriber->email, $this->getOption('delivery_job_id'), $queuedAt->diffForHumans(), $queuedAt->toString());
        }
    }

    public function isDelayAction()
    {
        return true;
    }
}
