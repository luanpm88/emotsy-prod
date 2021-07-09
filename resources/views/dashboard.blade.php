@extends('layouts.frontend')

@section('title', trans('messages.dashboard'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
    <h2 class="mt-4 pt-2">{!! trans('messages.frontend_dashboard_hello', ['name' => Auth::user()->displayName()]) !!}</h2>
    <p>{!! trans('messages.frontend_dashboard_welcome') !!}</p>

    <h4 class="mt-5">
        <span class="material-icons-outlined">
            donut_large
        </span>
        {{ trans("messages.used_quota") }}
    </h4>
    <p>{{ trans('messages.dashboard.credit.wording') }}</p>
    <div class="row quota_box">
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.sending_quota') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->getSendingQuotaUsage()) }}/{{ (Auth::user()->customer->getSendingQuota() == -1) ? '∞' : \Acelle\Library\Tool::format_number(Auth::user()->customer->getSendingQuota()) }}</span>
                        &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingQuotaUsage() }}
                    </div>
                </div>
                
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->getSendingQuotaUsagePercentage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->getSendingQuotaUsagePercentage() }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.list') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->listsCount()) }}/{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->maxLists()) }}</span>
                        &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayListsUsage() }}
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->listsUsage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->listsUsage() }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm mb-3">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.campaign') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->campaignsCount()) }}/{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->maxCampaigns()) }}</span>
                        &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayCampaignsUsage() }}
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->campaignsUsage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->campaignsUsage() }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="content-group-sm">
                <div class="d-flex mb-2">
                    <label class="fw-600 me-auto">{{ trans('messages.subscriber') }}</label>
                    <div class="pull-right  text-semibold">
                        <span class="text-muted">{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->readCache('SubscriberCount', 0)) }}/{{ \Acelle\Library\Tool::format_number(Auth::user()->customer->maxSubscribers()) }}</span>
                        &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySubscribersUsage() }}
                    </div>
                </div>
                <div class="progress progress-sm" style="height: 12px;">
                    <div class="progress-bar progress-bar-striped bg-{{ Auth::user()->customer->subscribersUsage() >= 80 ? 'danger' : 'primary' }}" style="width: {{ Auth::user()->customer->readCache('SubscriberUsage', 0) }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5">
        <span class="material-icons-outlined">
            history_toggle_off
            </span>
        {{ trans('messages.recently_sent_campaigns') }}
    </h4>

    @if (Auth::user()->customer->sentCampaigns()->count() == 0)
        <div class="empty-list">
            <span class="material-icons-outlined">
                dynamic_form
            </span>
            <span class="line-1">
                {{ trans('messages.no_sent_campaigns') }}
            </span>
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'class' => 'dashboard-campaign-select',
                    'name' => 'campaign_id',
                    'label' => '',
                    'value' => '',
                    'options' => Acelle\Model\Campaign::getSelectOptions(Auth::user()->customer, "done"),
                ])
            </div>
        </div>
        <div class="campaign-quickview-container" data-url="{{ action("CampaignController@quickView") }}"></div>
    @endif

    <h4 class="mt-5">
        <span class="material-icons-outlined">
            auto_graph
        </span>
        {{ trans('messages.list_growth') }}
    </h4>

    @if (Auth::user()->customer->lists()->count() == 0)
        <div class="empty-list">
            <span class="material-icons-outlined">
                dynamic_form
            </span>
            <span class="line-1">
                {{ trans('messages.no_saved_lists') }}
            </span>
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'class' => 'dashboard-list-select',
                    'name' => 'list_id',
                    'label' => '',
                    'value' => '',
                    'include_blank' => trans('messages.all'),
                    'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                ])
            </div>
        </div>
        <div class="list-quickview-container" data-url="{{ action("MailListController@quickView") }}"></div>
    @endif

    <h4 class="mt-5"><span class="material-icons-outlined">
        star_half
        </span> {{ trans('messages.top_5') }}
    </h4>

    <ul class="nav nav-tabs nav-underline" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="campaign_opens-tab" data-bs-toggle="tab" data-bs-target="#campaign_opens" role="button" role="tab" aria-controls="campaign_opens" aria-selected="true">
                {{ trans('messages.campaign_opens') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="campaign_clicks-tab" data-bs-toggle="tab" data-bs-target="#campaign_clicks" role="button" role="tab" aria-controls="campaign_clicks" aria-selected="false">
                {{ trans('messages.campaign_clicks') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="clicked_links-tab" data-bs-toggle="tab" data-bs-target="#clicked_links" role="button" role="tab" aria-controls="clicked_links" aria-selected="false">
                {{ trans('messages.clicked_links') }}
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="campaign_opens" role="tabpanel" aria-labelledby="campaign_opens-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topOpens(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <i class="number">{{ $num+1 }}</i>
                                <h6 class="mt-0 mb-0 text-semibold">
                                    <a href="{{ action('CampaignController@overview', $item->uid) }}">
                                        {{ $item->name }}
                                    </a>
                                </h6>
                                <p>
                                    {!! $item->displayRecipients() !!}
                                </p>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ number_with_delimiter($item->aggregate) }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.opens') }}</span>
                                    <br /><br />
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ number_with_delimiter($item->readCache('UniqOpenCount')) }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.uniq_opens') }}</span>
                                    <br /><br />
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ (null !== $item->lastOpen()) ? Acelle\Library\Tool::formatDateTime($item->lastOpen()->created_at) : "" }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_open') }}</span>
                                    <br /><br />
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
        <div class="tab-pane fade" id="campaign_clicks" role="tabpanel" aria-labelledby="campaign_clicks-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topClicks(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-5 col-md-5">
                                <i class="number">{{ $num+1 }}</i>
                                <h6 class="mt-0 mb-0 text-semibold">
                                    <a href="{{ action('CampaignController@overview', $item->uid) }}">
                                        {{ $item->name }}
                                    </a>
                                </h6>
                                <p>
                                    {!! $item->displayRecipients() !!}
                                </p>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->aggregate }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.clicks') }}</span>
                                    <br /><br />
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->urlCount() }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.urls') }}</span>
                                    <br /><br />
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    #
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_clicked') }}</span>
                                    <br /><br />
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
        <div class="tab-pane fade" id="clicked_links" role="tabpanel" aria-labelledby="clicked_links-tab">
            <ul class="modern-listing mt-0 top-border-none">
                @forelse (Acelle\Model\Campaign::topLinks(5, Auth::user()->customer)->get() as $num => $item)
                    <li>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <i class="number">{{ $num+1 }}</i>
                                <h6 class="mt-0 mb-0 text-semibold url-truncate">
                                    <a title="{{ $item->url }}" href="{{ $item->url }}" target="_blank">
                                        {{ $item->url }}
                                    </a>
                                </h6>
                                <p>
                                    {{ $item->aggregate }} {{ trans('messages.campaigns') }}
                                </p>
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    {{ $item->aggregate }}
                                </h5>
                                <span class="text-muted">{{ trans('messages.clicks') }}</span>
                                    <br /><br />
                            </div>
                            <div class="col-sm-2 col-md-2 text-left">
                                <h5 class="m-0 text-bold">
                                    #
                                </h5>
                                <span class="text-muted">{{ trans('messages.last_clicked') }}</span>
                                <br /><br />
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

    <h4 class="mt-5"><span class="material-icons-outlined">
        group_work
    </span> {{ trans('messages.activity_log') }}</h3>

    @if (Auth::user()->customer->logs()->count() == 0)
        <div class="empty-list">
            <span class="material-icons-outlined">
                dynamic_form
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
                    @foreach (Auth::user()->customer->logs()->take(20)->get() as $log)
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

    <br>
    <br>
@endsection
