<?php

namespace Acelle\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Acelle\Events\MailListSubscription;
use Acelle\Mail\MailListSubscriptionNotificationMailer;
use Acelle\Model\Setting;

class SendNotificationToListOwner  // SendSubscriptionNotificationToListOwner
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
     * @param  AdminLoggedIn  $event
     * @return void
     */
    public function handle(MailListSubscription $event)
    {
        /*
        // Send notification
        Mail::to(
            json_decode(json_encode([
                'email' => $event->subscriber->mailList->customer->user->email,
                'name'  => $event->subscriber->mailList->customer->displayName()])
            )
        )->send(
            new MailListSubscriptionNotificationMailer($event->subscriber)
        );
        */

        // trigger the event
        if (Setting::isYes('send_notification_email_for_list_subscription')) {
            // Sendmail here
        } else {
            // No notificaiton email sent, enable "send_notification_email_for_list_subscription" setting
        }
    }
}
