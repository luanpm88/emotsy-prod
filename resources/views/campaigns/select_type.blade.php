@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', trans('messages.select_campaign_type'))

@section('page_header')
<div class="page-title">
    <ul class="breadcrumb breadcrumb-caret position-right">
        <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
    </ul>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <h2 class="text-center mb-2">
            <span class="text-semibold">
                {{ trans('messages.select_campaign_type') }}
            </span>
        </h2>
        <p class="text-center mb-4 pb-2 px-5">{{ trans('messages.campaign.select_type.wording') }}</p>
        <ul class="modern-listsing big-icon mt-0 pb-2 px-0 border-top">
            @foreach (Acelle\Model\Campaign::types() as $key => $type)
                @if ($type == 'ab-test' && !Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('ab_testing'))
                @else
                    <li class="d-flex py-4 border-bottom box-shadow-sm">
                        <a href="{{ action("CampaignController@create", ["type" => $key]) }}">
                            <span class="d-block pt-1">
                                <img width="45px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/'.$key.'.svg') }}" />
                            </span>
                        </a>
                        <div class="d-flex pe-5 ps-2">
                            <div>
                                <h4 class="mb-1"><a href="{{ action("CampaignController@create", ["type" => $key]) }}">{{ trans('messages.campaign.type.' . $key) }}</a></h4>
                                <p class="mb-0">
                                    {{ trans('messages.campaign_intro_' . $key) }}
                                </p>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ action("CampaignController@create", ["type" => $key]) }}" class="btn btn-primary px-3 text-nowrap" style="border-radius:100px;">
                                {{ trans('messages.choose') }}
                                <span class="material-symbols-rounded ms-1">
                                    east
                                </span>
                            </a>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
        <div class="text-center">
            <a href="{{ action('CampaignController@index') }}" role="button" class="btn btn-light">
                <span class="material-symbols-rounded me-1">
                    west
                </span> {{ trans('messages.return_back') }}
            </a>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
@endsection
