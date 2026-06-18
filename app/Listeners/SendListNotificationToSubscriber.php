<?php

namespace Acelle\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Acelle\Events\MailListSubscription;
use Acelle\Events\MailListUnsubscription;
use Acelle\Model\Setting;

class SendListNotificationToSubscriber
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
        $subscriber = $event->subscriber;
        $list = $subscriber->mailList;

        if ($list->send_welcome_email) {
            // The subscriber is already saved by the time this fires. A welcome-email
            // failure (e.g. SES 403 "the from address does not..." when the list's
            // from_email isn't a verified sender) must NOT bubble up and 500 the
            // subscribe flow — log it and let the subscription stand. (SMTP-403)
            try {
                $list->sendSubscriptionWelcomeEmail($subscriber);
            } catch (\Exception $ex) {
                Log::warning('Subscription welcome email failed for "' . $subscriber->email . '" on list ' . $list->id . ': ' . $ex->getMessage());
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
        $subscriber = $event->subscriber;
        $list = $subscriber->mailList;

        if ($list->unsubscribe_notification) {
            // Same guard as the welcome email — a goodbye-email send failure must not
            // break the unsubscribe action itself. (SMTP-403)
            try {
                $list->sendUnsubscriptionNotificationEmail($subscriber);
            } catch (\Exception $ex) {
                Log::warning('Unsubscription notification email failed for "' . $subscriber->email . '" on list ' . $list->id . ': ' . $ex->getMessage());
            }
        }
    }

    /**
     * Handle the event.
     *
     * @param  AdminLoggedIn  $event
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'Acelle\Events\MailListSubscription',
            [SendListNotificationToSubscriber::class, 'handleMailListSubscription']
        );

        $events->listen(
            'Acelle\Events\MailListUnsubscription',
            [SendListNotificationToSubscriber::class, 'handleMailListUnsubscription']
        );
    }
}
