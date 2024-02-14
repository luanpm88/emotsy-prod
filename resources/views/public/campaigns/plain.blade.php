@extends('layouts.core.frontend_public', [
    'menu' => 'campaign',
])

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('head')      
    <script type="text/javascript" src="{{ AppUrl::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')
	
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
                @include('campaigns._steps', ['current' => 3])
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-0">{{ trans('messages.campaign.content_management') }}</h1>
            <h3>{{ trans('messages.campaign.email_content_plain') }}</h3>                
            <p>{{ trans('messages.campaign.email_content_plain.intro') }}</p>
                
            <form action="{{ action('Pub\CampaignController@plain', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" method="POST">
                {{ csrf_field() }}

                @include('helpers.form_control', [
                    'type' => 'textarea',
                    'class' => 'campaign-plain-text',
                    'name' => 'plain',
                    'value' => $campaign->plain,
                    'label' => '',
                    'help_class' => 'campaign',
                    'rules' => ['plain' => 'required'],
                ])

                <button class="btn btn-secondary">{{ trans('messages.campaign.plain.save') }}</button>
            </form>
        </div>
    </div>
@endsection
