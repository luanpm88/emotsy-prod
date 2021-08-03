@extends('layouts.core.backend')

@section('title', trans('messages.dashboard'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
@endsection

@section('content')
    
    @include('admin.notifications._top', ['notifications' => $notifications])

    <h1 class="mb-2 mt-4">{{ trans('messages.backend_dashboard_hello', ['name' => Auth::user()->displayName()]) }}</h1>
    <p>{{ trans('messages.backend_dashboard_welcome') }}</p>

    <div class="row mt-5">
        <div class="col-md-6">
            <h4 class="text-semibold"><span class="material-icons-round me-2">
people_outline
</span> {{ trans('messages.customers_growth') }}</h4>
            @include('admin.customers._growth_chart')
        </div>
        <div class="col-md-6">
            <h4 class="text-semibold"><i class="material-icons-outlined me-2">
assignment_turned_in
</i> {{ trans('messages.plans_chart') }}</h4>
            @include('admin.plans._pie_chart')
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h4 class="text-semibold">
                <span class="material-icons-outlined me-2">
assignment_turned_in
</span>
                {{ trans('messages.recent_subscriptions') }}
            </h4>
            <p style="margin-bottom: 30px" class="link-inline">{!! trans('messages.admin.dashboard.recent_subscriptions.wording', [ 'here' => action('Admin\SubscriptionController@index') ]) !!}</p>
            <ul class="modern-listing mt-0 mb-0 top-border-none type2">
                @forelse (Auth::user()->admin->recentSubscriptions() as $subscription)
                    <li class="">
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex">
                                    <div class="me-3 pt-1">
                                        <img width="35px" style="opacity:0.8" src="{{ url('images/icons/dark/SVG/plan.svg') }}">
                                    </div>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('Admin\CustomerController@subscriptions', $subscription->customer->uid) }}">
                                                {{ $subscription->plan->name }}
                                            </a>
                                        </h6>
                                        <p class="mb-0">
                                            <i class="material-icons-outlined me-1">
        person_outline
        </i>
                                            {{ $subscription->customer->user->displayName() }}
                                        </p>
                                    </div>
                                </div>
                                    
                                    
                            </div>
                            <div class="col-sm-4 col-md-4 text-left">
                                @if ($subscription->isEnded())
                                    <h5 class="no-margin stat-num">
                                        <span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->ends_at) }}</span>
                                    </h5>
                                    <span class="text-muted2">{{ trans('messages.subscription.ended_on') }}</span>
                                @elseif ($subscription->isActive())
                                    <h5 class="no-margin stat-num">
                                        <span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->ends_at) }}</span>
                                    </h5>
                                    <span class="text-muted2">{{ trans('messages.subscription.ends_on') }}</span>
                                @else
                                    <h5 class="no-margin stat-num">
                                        <span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->updated_at) }}</span>
                                    </h5>
                                    <span class="text-muted2">{{ trans('messages.subscription.updated_at') }}</span>
                                @endif
                            </div>
                            <div class="col-sm-3 col-md-3 text-left">
                                <span class="text-muted2 list-status pull-left">
                                    <span class="label label-flat bg-{{ $subscription->status }}">{{ trans('messages.subscription.status.' . $subscription->status) }}</span>
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li">
                        {{ trans('messages.empty_record_message') }}
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="col-md-6">
            <h4 class="text-semibold">
                <span class="material-icons-round me-2">
people_outline
</span>
                {{ trans('messages.recent_customers') }}
            </h4>
            <p style="margin-bottom: 30px" class="link-inline">{!! trans('messages.admin.dashboard.recent_customers.wording', [ 'here' => action('Admin\CustomerController@index') ]) !!}</p>
            <ul class="modern-listing mt-0 mb-0 top-border-none type2">
                @forelse(Auth::user()->admin->recentCustomers() as $customer)
                    <li class="">
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <img width="40" class="rounded-circle shadow-sm mr-10 pull-left" src="{{ $customer->user->getProfileImageUrl() }}" alt="">
                                    </div>
                                    <div>
                                        <h6 class="mt-0 mb-0 text-semibold">
                                            <a href="{{ action('Admin\CustomerController@edit', $customer->uid) }}">
                                                {{ $customer->user->displayName() }}
                                            </a>
                                        </h6>
                                        <p class="mb-0 admin-line admin-recent-sencond-line" title="{{ $customer->user->email }}">
                                            {{ $customer->user->email }}
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-sm-4 col-md-4 text-left">
                                <h6 class="no-margin text-semibold">
                                    {{ Tool::formatDateTime($customer->created_at) }}
                                </h6>
                                <span class="">{{ trans('messages.created_at') }}</span>
                            </div>
                            <div class="col-sm-3 col-md-3 text-left">
                                <span class="text-muted2 list-status pull-left">
                                    <span class="label label-flat bg-{{ $customer->status }}">{{ trans('messages.subscription_status_' . $customer->status) }}</span>
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="empty-li">
                        {{ trans('messages.empty_record_message') }}
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <h4 class="text-semibold mt-5">
        <span class="material-icons-outlined me-2">
restore
</span>
        {{ trans('messages.activities') }}
    </h4>
    <p style="margin-bottom: 30px" class="link-inline">{!! trans('messages.admin.dashboard.recent_activity.wording', [ 'here' => action('Admin\CustomerController@index') ]) !!}</p>
    @if (\Auth::user()->admin->getLogs()->count() == 0)
        <div class="empty-list">
            <span class="material-icons-outlined me-2">
history_toggle_off
</span>
            <span class="line-1">
                {{ trans('messages.no_activity_logs') }}
            </span>
        </div>
    @else
        <div class="action-log-box">
            <!-- Timeline -->
            <div class="">
                <div class="mt-4">
                    @foreach (\Auth::user()->admin->getLogs()->take(20)->get() as $log)
                        <!-- Sales stats -->
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <a href="#"><img width="50px" class="rounded-circle shadow-sm" src="{{ $log->customer->user->getProfileImageUrl() }}" alt=""></a>
                            </div>

                            <div class="card px-0 shadow-sm container-fluid">
                                <div class="card-body pt-2">
                                    <div class="d-flex align-items-center pt-1">
                                        <label class="panel-title text-semibold my-0 fw-600">{{ $log->customer->user->displayName() }}</label>
                                        <div class="d-flex align-items-center ms-auto text-muted">
                                            <span style="font-size: 18px" class="material-icons-outlined ms-auto me-2">
                                                history
                                            </span>
                                            <div class="">
                                                <span class="heading-text"><i class="icon-history position-left text-success"></i> {{ Tool::dateTime($log->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0">{!! $log->message() !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /sales stats -->
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="sub-section mb-4 mt-5" style="margin-top: 60px">
        <h4 class="text-semibold mt-5"><span class="material-icons-outlined me-2">
            running_with_errors
            </span> {{ trans('messages.resources_statistics') }}</h4>
        <p>{{ trans('messages.resources_statistics_intro') }}</p>
        <div class="row">
            <div class="col-md-6">
                <ul class="dotted-list topborder section">
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-icons-round me-2">
people_outline
</span> {{ trans('messages.customers') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllCustomers()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined me-2">
assignment_turned_in
</span> {{ trans('messages.subscriptions') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllSubscriptions()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><i class="material-icons-outlined me-2">
assignment_turned_in
</i> {{ trans('messages.plans') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllPlans()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined me-2">
contact_mail
</span> {{ trans('messages.lists') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllLists()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-icons-round me-2">
people_outline
</span> {{ trans('messages.subscribers') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ number_with_delimiter($subscribersCount) }}</mc:flag>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="dotted-list topborder section">
                    <li>
                        <div class="unit size1of2">
                            <strong><i class="icon-user-tie"></i> {{ trans('messages.admins') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllAdmins()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined me-2">
people
</span> {{ trans('messages.admin_groups') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllAdminGroups()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined">
dns
</span> {{ trans('messages.sending_servers') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllSendingServers()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined">
public
</span> {{ trans('messages.sending_domains') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ $sendingDomainsCount }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined me-2">
forward_to_inbox
</span> {{ trans('messages.campaigns') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ Auth::user()->admin->getAllCampaigns()->count() }}</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong><span class="material-icons-outlined me-2">
schedule
</span> {{ trans('messages.automations') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>{{ number_with_delimiter($automationsCount) }}</mc:flag>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
