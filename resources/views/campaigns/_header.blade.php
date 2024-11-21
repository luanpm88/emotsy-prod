@php
	$menu = $menu ?? null;
@endphp

<div class="mt-3">
    <div class="border-bottom mb-4">
        <div class="" style="width: 100%">
            <div class="page-title pt-0">
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                </ul>
            </div>
            <h2 class="mb-0 mb-3 d-flex align-items-center">
                <span class="text-semibold">
                    <img width="25px" class="icon-img d-inline-block me-2" src="{{ url('images/icons/'.$campaign->type.'.svg') }}" />
                    <span>{{ $campaign->name }}</span>
                </span>
            </h1>
        </div>
        <div class="ms-auto">
            <a target="_blank" href="{{ action('Pub\CampaignController@overview', ['uid' => $campaign->uid, 'customer_uid' => $campaign->customer->uid]) }}" class="btn btn-default">
                <span class="material-symbols-rounded me-1">public</span>
                {{ trans('messages.campaign.public_report') }}
            </a>
        </div>
    </div>
</div>