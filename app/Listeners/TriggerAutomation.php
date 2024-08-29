<?php

namespace Acelle\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUnsubscription;
use Acelle\Events\SubscribersTagsUpdate;

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
     * @param  MailListSubscription  $event
     * @return void
     */
    public function handleMailListSubscription(MailListSubscription $event)
    {
        $automations = $event->subscriber->mailList->automations;
        $automations = $automations->filter(function ($auto, $key) {
            return $auto->isActive() && (
                $auto->getTriggerType() == Automation2::TRIGGER_TYPE_WELCOME_NEW_SUBSCRIBER
            );
        });

        foreach ($automations as $auto) {
            if (is_null($auto->getAutoTriggerFor($event->subscriber))) {
                $segments = $auto->getSegments();

                // If there is no segment condition, trigger the contact
                if ($segments->isEmpty()) {
                    $trigger = $auto->initTrigger($event->subscriber);
                    $trigger->check($manually = true); // manually here means "by individual users"

                    continue;
                }

                $matched = false;
                foreach ($segments as $segment) {
                    if ($segment->isSubscriberIncluded($event->subscriber)) {
                        $matched = true;
                        break;
                    }
                }

                if ($matched) {
                    $trigger = $auto->initTrigger($event->subscriber);
                    $trigger->check($manually = true);
                }
            }
        }
    }

    /**
     * Handle the event.
     *
     * @param  MailListSubscription  $event
     * @return void
     */
    public function handleMailListUnsubscription(MailListUnsubscription $event)
    {
        $automations = $event->subscriber->mailList->automations;
        $automations = $automations->filter(function ($auto, $key) {
            return $auto->isActive() && (
                $auto->getTriggerType() == Automation2::TRIGGER_TYPE_SAY_GOODBYE_TO_SUBSCRIBER
            );
        });

        foreach ($automations as $auto) {
            if (is_null($auto->getAutoTriggerFor($event->subscriber))) {
                $forceTriggerUnsubscribedContact = true;
                $trigger = $auto->initTrigger($event->subscriber, $forceTriggerUnsubscribedContact);
                $trigger->check($manually = true);
            }
        }
    }

    public function handleSubscribersTagsUpdate(SubscribersTagsUpdate $event)
    {
        $automations = $event->subscriber->mailList->automations;
        $automations = $automations->filter(function ($auto, $key) {
            return $auto->isActive() && (
                $auto->getTriggerType() == Automation2::TRIGGER_TAG_BASED
            );
        });

        foreach ($automations as $auto) {
            $tags = $auto->getTriggerAction()->getOption('tags');
            if ($event->subscriber->hasTags($tags)) {
                $existingTrigger = $auto->getAutoTriggerFor($event->subscriber);

                if (is_null($existingTrigger)) {
                    $trigger = $auto->initTrigger($event->subscriber);
                    $trigger->check($manually = true);
                } else {
                    $auto->logger()->info('Tag matched but already triggered');
                }
            }
        }
    }

    // Subscribe to many events
    public function subscribe($events)
    {
        $events->listen(
            'Acelle\Events\MailListSubscription',
            [TriggerAutomation::class, 'handleMailListSubscription']
        );

        $events->listen(
            'Acelle\Events\MailListUnsubscription',
            [TriggerAutomation::class, 'handleMailListUnsubscription']
        );

        $events->listen(
            SubscribersTagsUpdate::class,
            [TriggerAutomation::class, 'handleSubscribersTagsUpdate']
        );
    }
}
