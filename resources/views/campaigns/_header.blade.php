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
                <span class="text-center text-muted badge badge-info ms-2">{{ trans('messages.campaign.type.' . $campaign->type) }}</span>
            </h2>
            @include("campaigns._menu", [
                'menu' => $menu,
            ])
        </div>
    </div>
</div>