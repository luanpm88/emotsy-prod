<?php

namespace Acelle\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Acelle\Library\Notification\CronJob;
use Acelle\Library\Notification\SystemUrl;
use Acelle\Events\AdminLoggedIn;
use Acelle\Model\Subscription;
use Acelle\Model\Notification;

class AdminLoggedInListener
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
    public function handle(AdminLoggedIn $event)
    {
        // Check CronJob
        CronJob::check();
        
        // Check System URL
        SystemUrl::check();

        // Check for pending subscriptions
        $this->checkForClaimedSubscriptions();

        // Check for PHP version
        $this->checkForPhpVersion();
    }

    public function checkForClaimedSubscriptions()
    {
        $title = trans('messages.admin.notification.subscription.pending.title');
        $count = Subscription::claimed()->count();
        if ($count > 0) {
            Notification::info([
                'title' => $title,
                'message' => trans('messages.admin.notification.subscription.pending', [ 'count' => $count ]),
            ]);
        } else {
            Notification::cleanupDuplicateNotifications($title);
        }
    }

    public function checkForPhpVersion()
    {
        $title = 'PHP version is no longer supported';

        if (version_compare(PHP_VERSION, config('custom.php_recommended'), '<')) {
            Notification::error([
                'title' => $title,
                'message' => sprintf("Your hosting server's PHP version %s is no longer supported, please upgrade to version %s or higher", PHP_VERSION, config('custom.php_recommended')),
            ]);
        } else {
            Notification::cleanupDuplicateNotifications($title);
        }
    }
}
