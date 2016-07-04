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
        \Mailixa\Model\Segment::class => \Mailixa\Policies\SegmentPolicy::class,
        \Mailixa\Model\Layout::class => \Mailixa\Policies\LayoutPolicy::class,
        \Mailixa\Model\Template::class => \Mailixa\Policies\TemplatePolicy::class,
        \Mailixa\Model\Campaign::class => \Mailixa\Policies\CampaignPolicy::class,
        \Mailixa\Model\UserGroup::class => \Mailixa\Policies\UserGroupPolicy::class,
        \Mailixa\Model\SendingServer::class => \Mailixa\Policies\SendingServerPolicy::class,
        \Mailixa\Model\BounceHandler::class => \Mailixa\Policies\BounceHandlerPolicy::class,
        \Mailixa\Model\FeedbackLoopHandler::class => \Mailixa\Policies\FeedbackLoopHandlerPolicy::class,
        \Mailixa\Model\SendingDomain::class => \Mailixa\Policies\SendingDomainPolicy::class,
        \Mailixa\Model\Language::class => \Mailixa\Policies\LanguagePolicy::class,
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
