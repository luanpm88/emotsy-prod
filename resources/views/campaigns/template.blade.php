@extends('layouts.core.frontend', [
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
    <form action="{{ action('CampaignController@template', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <h1 class="mt-0">{{ trans('messages.email_content') }}</h1>

        <ul class="nav nav-tabs nav-tabs-top top-divided text-semibold">
            @if ($campaign->type != 'plain-text')
                <li class="active">
                    <a href="#top-justified-divided-tab1" data-toggle="tab">
                        <i class="icon-circle-code"></i> {{ trans('messages.html_version') }}
                    </a>
                </li>
            @endif
            <li class="plain_text_li {{ ($campaign->type == 'plain-text') ? " active" : "" }}">
                <a href="#top-justified-divided-tab2" data-toggle="tab">
                    <span class="material-symbols-rounded">auto_awesome</span> {{ trans('messages.plain_text_version') }}
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            @if ($campaign->type != 'plain-text')
                <div class="tab-pane active" id="top-justified-divided-tab1">
                    @include('helpers.form_control', ['type' => 'textarea',
                        'class' => 'clean-editor',
                        'name' => 'html',
                        'label' => '',
                        'value' => $campaign->getTemplateContent(),
                        'rules' => $rules,
                        'help_class' => 'campaign'
                    ])
                </div>
            @endif

            <div class="tab-pane{{ ($campaign->type == 'plain-text') ? " active" : "" }}" id="top-justified-divided-tab2">
                @include('helpers.form_control', ['type' => 'textarea',
                    'class' => 'form-control plain_text_content',
                    'name' => 'plain',
                    'label' => '',
                    'value' => $campaign->plain,
                    'rules' => $rules,
                    'help_class' => 'campaign'
                ])
            </div>
        </div>

        @include('elements._tags', ['tags' => Acelle\Model\Template::tags($campaign->defaultMailList)])

        <hr>
        <div class="text-center">
            <button class="btn btn-primary px-4 py-2" style="border-radius:100px;">{{ trans('messages.save_and_next') }} <span class="material-symbols-rounded">arrow_forward</span> </button>
        </div>
    <form>
@endsection
