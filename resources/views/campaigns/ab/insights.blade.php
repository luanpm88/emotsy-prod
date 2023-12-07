@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', $campaign->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('page_header')

    @include("campaigns._header", [
        'menu' => 'ab-test',
    ])

@endsection

@section('content')
    <div>
        <div class="row pt-2">
            @if ($campaign->ab_split_subject)
                <div class="col-md-4">
                    <h4>{{ trans('messages.campaign.ab.insights.subject_lines.title') }}</h4>
                    <h5>{{ trans('messages.email_subject') }}</h5>
                    <div class="d-flex mb-1">
                        <div class="me-2">
                            <span>{{ trans('messages.campaign.ab.version_a') }}:</span>
                        </div>
                        <div>
                            <p class="mb-2">
                                {{ $campaign->subject }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex mb-0">
                        <div class="me-2">
                            <span>{{ trans('messages.campaign.ab.version_b') }}:</span>
                        </div>
                        <div>
                            <p class="mb-2">
                                {{ $campaign->campaignB->subject }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($campaign->ab_split_content)
                <div class="col-md-8">
                    <h4>{{ trans('messages.campaign.ab.split_options') }}</h4>
                    <p>{{ trans('messages.campaign.ab.insights.split_options.title') }}</p>
                    <div split-control="container" class="position-relative">
                        <div class="d-flex border" style="width:100%;">
                            <div split-control="a-box" style="width:{{ $campaign->abGetVersionABPercent() }}%;" class="border-end bg-gradient bg-info bg-opacity-10">
                                <div class="text-center p-3">
                                    <div class="text-truncate">
                                        <strong>{{ trans('messages.campaign.ab.version_a') }}</strong>
                                    </div>
                                    <div class="py-3 text-truncate">
                                        <strong class="fw-semibold fs-6"><span split-control="value-a">{{ $campaign->abGetVersionABPercent() }}</span>%</strong>
                                    </div>
                                    <div class="text-truncate">
                                        (~<span split-control="aprox-b">{{ number_format(round($campaign->abGetApproxRecipientsABVersion())) }}</span> {{ trans('messages.recipients') }})
                                    </div>
                                </div>
                            </div>
                            <div style="width:{{ $campaign->abGetVersionABPercent() }}%;" class="{{ $campaign->ab_winning_percent > 0 ? 'border-end' : '' }} bg-gradient  bg-info bg-opacity-10">
                                <div class="text-center p-3">
                                    <div class="text-truncate">
                                        <strong>{{ trans('messages.campaign.ab.version_b') }}</strong>
                                    </div>
                                    <div class="py-3 text-truncate">
                                        <strong class="fw-semibold fs-6"><span split-control="value-b">{{ $campaign->abGetVersionABPercent() }}</span>%</strong>
                                    </div>
                                    <div class="text-truncate">
                                        (~<span split-control="aprox-b">{{ number_format(round($campaign->abGetApproxRecipientsABVersion())) }}</span> {{ trans('messages.recipients') }})
                                    </div>
                                </div>
                            </div>
                            <div split-control="winning-box" style="width:{{ $campaign->ab_winning_percent }}%;" class="bg-gradient bg-success bg-opacity-10">
                                <div class="text-center p-3">
                                    <div class="text-truncate">
                                        <strong>{{ trans('messages.campaign.ab.version_winning') }}</strong>
                                    </div>
                                    <div class="py-3 text-truncate">
                                        <strong class="fw-semibold fs-6"><span split-control="value-winning">{{ $campaign->ab_winning_percent }}</span>%</strong>
                                    </div>
                                    <div class="text-truncate">
                                        (~<span split-control="aprox-b">{{ number_format(round($campaign->abGetApproxRecipientsWinningVersion())) }}</span> {{ trans('messages.recipients') }})
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($campaign->ab_winning_percent == 0)
                        <p class="mt-3 alert alert-info bg-light text-dark rounded">{{ trans('messages.campaign.ab.winning_version.none.intro') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <h4 class="mt-5">{{ trans('messages.campaign.ab.test_result') }}</h4>
        <div class="row">
            <div class="col-md-4">
                <h5>{{ trans('messages.open_rate') }}</h5>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_a') }}</span>
                        <span class="pull-right text-primary fw-semibold">
                            {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}
                        </span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_b') }}</span>
                        <span class="pull-right text-primary fw-semibold">
                            {{ number_to_percentage($campaign->campaignB->readCache('UniqOpenRate')) }}
                        </span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->campaignB->readCache('UniqOpenRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@openLog', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-light bg-teal-600">{{ trans('messages.open_log') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            </div>

            <div class="col-md-4">
                <h5>{{ trans('messages.click_rate') }}</h5>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_a') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_b') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->campaignB->readCache('ClickedRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->campaignB->readCache('ClickedRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@clickLog', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-light bg-teal-600">{{ trans('messages.click_log') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            </div>

            <div class="col-md-4">
                <h5>{{ trans('messages.ab.failed_rate') }}</h5>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_a') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->readCache('FailedDeliveredRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('FailedDeliveredRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_b') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->campaignB->readCache('FailedDeliveredRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->campaignB->readCache('FailedDeliveredRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@openLog', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-light bg-teal-600">{{ trans('messages.tracking_log') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            </div>
        </div>
        
        {{-- <div class="row mt-5">
            <div class="col-md-4">
                <h5>{{ trans('messages.campaign.soft_bounced') }}</h5>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_a') }}</span>
                        <span class="pull-right text-primary fw-semibold">
                            {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}
                        </span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_b') }}</span>
                        <span class="pull-right text-primary fw-semibold">
                            {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}
                        </span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@bounceLog', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-light bg-teal-600">{{ trans('messages.bounce_log') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            </div>
            
            <div class="col-md-4">
                <h5>{{ trans('messages.campaign.hard_bounced') }}</h5>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_a') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_b') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@bounceLog', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-light bg-teal-600">{{ trans('messages.bounce_log') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            </div>

            <div class="col-md-4">
                <h5>{{ trans('messages.feedback') }}</h5>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_a') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="content-group-sm mb-3">
                    <div class="d-flex">
                        <span class="text-semibold mt-0 mb-0 me-auto">{{ trans('messages.campaign.ab.version_b') }}</span>
                        <span class="pull-right text-primary fw-semibold">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
                    </div>
                    <div class="progress progress-sm" style="height:5px;">
                        <div class="progress-bar bg-color1" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@feedbackLog', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-light bg-teal-600">{{ trans('messages.feedback_log') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            </div>
        </div> --}}


        <div class="row mt-5">
            <div class="col-md-6">
                @include('campaigns.ab._open_performance_chart')
            </div>

            <div class="col-md-6">
                @include('campaigns.ab._click_performance_chart')
            </div>
        </div>

        
    </div>
@endsection
