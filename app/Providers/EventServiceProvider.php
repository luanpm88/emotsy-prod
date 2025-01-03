<?php

namespace Acelle\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Acelle\Events\CampaignUpdated' => [
            'Acelle\Listeners\CampaignUpdatedListener',
        ],
        'Acelle\Events\MailListUpdated' => [
            'Acelle\Listeners\MailListUpdatedListener',
        ],
        'Acelle\Events\UserUpdated' => [
            'Acelle\Listeners\UserUpdatedListener',
        ],
        'Acelle\Events\CronJobExecuted' => [
            'Acelle\Listeners\CronJobExecutedListener',
        ],
        'Acelle\Events\AdminLoggedIn' => [
            'Acelle\Listeners\AdminLoggedInListener',
        ],
        'Acelle\Events\MailListSubscription' => [
            /* Use subscriber instead */
            // 'Acelle\Listeners\SendListNotificationToOwner',
            // 'Acelle\Listeners\SendListNotificationToSubscriber',
            // 'Acelle\Listeners\TriggerAutomation',
        ],
        'Acelle\Events\MailListUnsubscription' => [
            /* Use subscriber instead */
            // 'Acelle\Listeners\SendListNotificationToOwner',
            // 'Acelle\Listeners\SendListNotificationToSubscriber',
            // 'Acelle\Listeners\TriggerAutomation',
        ],
        'Acelle\Events\MailListImported' => [
            'Acelle\Listeners\TriggerAutomationForImportedContacts',
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'Acelle\Listeners\TriggerAutomation',
        'Acelle\Listeners\SendListNotificationToOwner',
        'Acelle\Listeners\SendListNotificationToSubscriber',
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
