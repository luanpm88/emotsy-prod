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
    <h1>
        <span class="text-semibold"><span class="material-symbols-rounded">schedule</span> {{ trans('messages.select_campaign_type') }}</span>
    </h1>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <ul class="modern-listing big-icon no-top-border-list mt-0">
            @foreach (Acelle\Model\Campaign::types() as $key => $type)
                <li class="py-3">
                    <a href="{{ action("CampaignController@create", ["type" => $key]) }}" class="btn btn-primary">{{ trans('messages.choose') }}</a>
                    <div class="d-flex pe-4">
                        <a href="{{ action("CampaignController@create", ["type" => $key]) }}">
                            <span class="pt-1 d-block">
                                <img width="40px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/'.$key.'.svg') }}" />
                            </span>
                        </a>
                        <div>
                            <h4 class="mb-1"><a href="{{ action("CampaignController@create", ["type" => $key]) }}">{{ trans('messages.' . $key) }}</a></h4>
                            <p>
                                {{ trans('messages.campaign_intro_' . $key) }}
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach

            <li class="py-3">
                <a link-method="POST" href="{{ action("CampaignABController@create") }}" class="btn btn-primary">{{ trans('messages.choose') }}</a>
                <div class="d-flex pe-4">
                    <a href="{{ action("CampaignABController@create") }}">
                        <span class="pt-1 d-block">
                            <img width="40px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/ab-test.svg') }}" />
                        </span>
                    </a>
                    <div>
                        <h4 class="mb-1"><a href="{{ action("CampaignABController@create") }}">{{ trans('messages.campaign.ab.title') }}</a></h4>
                        <p>
                            {{ trans('messages.campaign.ab.intro') }}
                        </p>
                    </div>
                </div>
                    
            </li>

        </ul>
        <hr>
        <div class="">
            <a href="{{ action('CampaignController@index') }}" role="button" class="btn btn-secondary">
                <i class="icon-cross2"></i> {{ trans('messages.cancel') }}
            </a>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
@endsection
