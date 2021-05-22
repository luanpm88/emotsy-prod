<?php

namespace Acelle\Listeners;

use Acelle\Events\SubscriberAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Acelle\Library\Log as MailLog;

use Acelle\Model\Automation2;

class TriggerAutomation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscriberAdded  $event
     * @return void
     */
    public function handle(SubscriberAdded $event)
    {
        MailLog::info('[SubscriberAdded::TriggerAutomation] triggered');
        $automations = $event->subscriber->mailList->automations;
        $automations = $automations->filter(function ($auto, $key) {
            return $auto->isActive() && (
                $auto->getTriggerType() == Automation2::TRIGGER_TYPE_WELCOME_NEW_SUBSCRIBER || 
                $auto->getTriggerType() == Automation2::TRIGGER_TYPE_SAY_GOODBYE_TO_SUBSCRIBER
            );
        });

        foreach ($automations as $auto) {
            // Trigger automation for the newly added subscriber or leaving subscriber
            // Why not check? ->autoTriggers()->where(['subscriber_id' => 1392])->exists()
            // No, keep it simple, the Listener's responsibility is to trigger, not validation
            // and, moreover, there might be triggers that occur more than one time
            $auto->initTrigger($event->subscriber);
        }
    }
}
