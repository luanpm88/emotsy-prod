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
        $this->campaign->logger()->info('OKIE done?');

        $autoTrigger = AutoTrigger::find($this->autoTriggerId);
        $autoTrigger->withUpdateLock(function($trigger) {
            // Mark the related trigger action as done
            $action = $trigger->getActionById($this->actionId);
            $action->setOption('sent_at', now()->toString());
            $trigger->updateAction($action);


            var_dump($action->getOptions());

            $this->campaign->logger()->info('OKIE done!');
        });
    }
}
