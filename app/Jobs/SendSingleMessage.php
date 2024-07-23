<?php

namespace Acelle\Jobs;

use Acelle\Model\AutoTrigger;
use Acelle\Model\Subscriber;
use Acelle\Model\SendingServer;
use Acelle\Model\Subscription;
use Throwable;

class SendSingleMessage extends SendMessage
{
    protected $autoTriggerId;
    protected $auutoTrigger;
    protected $actionId;
    protected $action;

    public function __construct($campaign, Subscriber $subscriber, SendingServer $server, Subscription $subscription = null, $autoTriggerId = null, $actionId = null)
    {
        parent::__construct($campaign, $subscriber, $server, $subscription);

        $this->autoTriggerId = $autoTriggerId;
        $this->actionId = $actionId;
    }

    public function afterSuccess()
    {
        $this->campaign->logger()->info('Updating trigger status (sent_at)');

        $this->getAutoTrigger()->withUpdateLock(function($trigger) {
            // Mark the related trigger action as done

            $action = $this->getAction();
            $action->setOption('sent_at', now()->toString());
            $action->save();
            $this->campaign->logger()->info('Updated trigger status (sent_at)');
        });
    }

    public function getAutoTrigger()
    {
        if (is_null($this->auutoTrigger)) {
            $this->autoTrigger = AutoTrigger::find($this->autoTriggerId);
        }

        return $this->autoTrigger;
    }

    public function getAction()
    {
        if (is_null($this->action)) {
            $this->action = $this->getAutoTrigger()->getActionById($this->actionId);
        }

        return $this->action;
    }

    public function handle()
    {
        try {
            $this->send();
        } catch (Throwable $ex) {
            $this->getAutoTrigger()->withUpdateLock(function($trigger) use ($ex) {
                // Mark the related trigger action as done
                $action = $this->getAction();
                $action->setOption('error', $ex->getMessage());
                $action->save();
                $this->campaign->logger()->info('Error sending trigger email: '.$ex->getMessage());
            });
        }
    }
}
