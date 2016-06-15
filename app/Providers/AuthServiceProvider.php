<?php

namespace Mailixa\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Mailixa\Model' => 'Mailixa\Policies\ModelPolicy',
        \Mailixa\Model\User::class => \Mailixa\Policies\UserPolicy::class,
        \Mailixa\Model\Contact::class => \Mailixa\Policies\ContactPolicy::class,
        \Mailixa\Model\MailList::class => \Mailixa\Policies\MailListPolicy::class,
        \Mailixa\Model\Subscriber::class => \Mailixa\Policies\SubscriberPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
