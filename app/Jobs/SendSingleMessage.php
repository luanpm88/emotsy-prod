<?php

namespace Acelle\Jobs;

use Acelle\Model\AutoTrigger;
use Acelle\Model\Subscriber;
use Acelle\Model\SendingServer;
use Acelle\Model\Subscription;

class SendSingleMessage extends SendMessage
{
    protected $autoTriggerId;
    protected $actionId;

    public function __construct($campaign, Subscriber $subscriber, SendingServer $server, Subscription $subscription = null, $autoTriggerId = null, $actionId = null)
    {
        parent::__construct($campaign, $subscriber, $server, $subscription);

        $this->autoTriggerId = $autoTriggerId;
        $this->actionId = $actionId;
    }

    public function afterSuccess()
    {
        $this->campaign->logger()->info('Updating trigger status (sent_at)');

        $autoTrigger = AutoTrigger::find($this->autoTriggerId);
        $autoTrigger->withUpdateLock(function($trigger) {
            // Mark the related trigger action as done
            $action = $trigger->getActionById($this->actionId);
            $action->setOption('sent_at', now()->toString());
            $trigger->updateAction($action);
            $this->campaign->logger()->info('Updated trigger status (sent_at)');
        });
    }
}
