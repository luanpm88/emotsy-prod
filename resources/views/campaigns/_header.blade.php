@php
	$menu = $menu ?? null;
@endphp
<div class="topfix-header bg-light">
    <div class="topfix-container border-bottom d-flex align-items-center shadow-sm py-3 mb-4">
        <div class="topfix-search text-center" style="width: 100%">
            <div class="page-title pt-0">
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                </ul>
            </div>
            <h3 class="mb-0 mb-3 d-flex align-items-center justify-content-center">
                <span class="text-semibold">
                    <img width="25px" class="icon-img d-inline-block me-2" src="{{ url('images/icons/'.$campaign->type.'.svg') }}" />
                    <span>{{ $campaign->name }}</span>
                </span>
                <span class="d-flex" title='{{ $campaign->status == Acelle\Model\Campaign::STATUS_ERROR ? $campaign->last_error : '' }}' data-popup='tooltip'>
                    <span class="text-center text-muted badge badge-info ms-2 bg-{{ $campaign->status }}">{{ trans('messages.campaign_status_' . $campaign->status) }}</span>
                </span>
                <span class="text-center text-muted badge badge-info ms-2">{{ trans('messages.campaign.type.' . $campaign->type) }}</span>
            </h3>

            {{-- <div class="text-center">
                <span class="mb-0 border py-1 px-4 my-0 bg-white d-inline-block" style="border-radius:100px;">
                    <span class="material-symbols-rounded">troubleshoot</span>  {{ trans('messages.campaign.overview_statistics') }}
                </span>
            </div> --}}
            
            @include("campaigns._menu", [
                'menu' => $menu,
            ])

        </div>
    </div>
</div>

{{-- <div class="page-title">
	<ul class="breadcrumb breadcrumb-caret position-right">
		<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		<li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
	</ul>
    <div class="d-flex mt-4">
        <div>
            <h1 class="d-flex align-items-center">
                <span class="text-semibold mr-3">{{ $campaign->name }}</span>
                <span class="d-flex" title='{{ $campaign->status == Acelle\Model\Campaign::STATUS_ERROR ? $campaign->last_error : '' }}' data-popup='tooltip'>
                    <span class="label label-flat bg-{{ $campaign->status }}">{{ trans('messages.campaign_status_' . $campaign->status) }}</span>
                </span>
            </h1>
        </div>
        <div class="ms-auto">
            <a target="_blank" href="{{ action('Pub\CampaignController@overview', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-default">
                <span class="material-symbols-rounded me-1">public</span>
                {{ trans('messages.campaign.public_report') }}
            </a>
        </div>
    </div>
        
</div> --}}
