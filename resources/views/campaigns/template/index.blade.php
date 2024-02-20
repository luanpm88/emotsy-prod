@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
    
@section('head')      
    <script type="text/javascript" src="{{ AppUrl::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/editor.js') }}"></script>

    <!-- Dropzone -->    
	<script type="text/javascript" src="{{ AppUrl::asset('core/dropzone/dropzone.js') }}"></script>
    @include('helpers._dropzone_lang')
	<link href="{{ AppUrl::asset('core/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css">
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
        <div class="col-md-10 mb-0">
            @if ($campaign->isABTest() && $campaign->ab_split_content)
                @include('campaigns.ab._template_variations')
            @endif
            
            <h3 class="mt-2">{{ trans('messages.campaign.email_content') }}</h3>  

            <div class="sub-section d-flex">
                <div class=" mr-auto pr-2">                    
                    <p>{{ trans('messages.campaign.email_content.intro') }}</p>
                        
                    <div class="media-left">
                        <div class="main">
                            <label>{{ trans('messages.campaign.html_email') }}</label>
                            <p>{{ trans('messages.campaign.html_email.last_edit', [
                                'date' => Auth::user()->customer->formatDateTime($campaign->updated_at, 'datetime_full'),
                            ]) }}</p>

                            <p class="mt-20">
                                @if (in_array(Acelle\Model\Setting::getBuilderConfig(), ['both','pro']) && $campaign->template->builder)
                                    <a href="{{ action('CampaignController@templateEdit', [
                                        'uid' => $campaign->uid,
                                        'version_b' => request()->version_b,
                                    ]) }}" class="btn btn-primary me-1 template-compose">
                                        {{ trans('messages.campaign.email_builder_pro') }}
                                    </a>
                                @endif
                                @if (in_array(Acelle\Model\Setting::getBuilderConfig(), ['both','classic']))
                                    <a href="{{ action('CampaignController@builderClassic', [
                                        'uid' => $campaign->uid,
                                        'version_b' => request()->version_b,
                                    ]) }}" class="btn btn-default template-compose-classic">
                                        {{ trans('messages.campaign.email_builder_classic') }}
                                    </a>
                                @endif
                                <a href="{{ action('CampaignController@templateCreate', [
                                    'uid' => $campaign->uid,
                                    'version_b' => request()->version_b,
                                ]) }}" class="btn btn-link bg-grey-600 me-1">
                                    {{ trans('messages.campaign.change_template') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="template-thumb-container ml-4">
                        @if ($campaign->isABTest() && request()->version_b)
                            <img class="automation-template-thumb" src="{{ $campaign->campaignB->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                        @else
                            <img class="automation-template-thumb" src="{{ $campaign->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                        @endif
                        <a
                            onclick="popupwindow('{{ action('CampaignController@preview', [
                                'uid' => $campaign->uid,
                                'version_b' => request()->version_b,
                            ]) }}', `{{ $campaign->name }}`, 800)"
                            href="javascript:;"
                            class="btn btn-primary" style="display:none"
                        >
                            {{ trans('messages.automation.template.preview') }}
                        </a>                           
                    </div>
                </div>
            </div>

            @if ($spamscore)
                <div class="sub-section">
                    <h2 class="mt-0 mb-3">{{ trans('messages.campaign.spam_score') }}</h2>
                    <p>{!! trans('messages.campaign.score.intro') !!}</p>
                    <a href="#" id="calculate-score" class="btn btn-primary bg-grey-600 me-1">
                        {{ trans('messages.campaign.check_spam_score') }}
                    </a>
                </div>
            @endif

            <div class="sub-section">   
                <h2 class="mt-0 mb-3">{{ trans('messages.campaign.attachment') }}</h2>
                <p>{{ trans('messages.campaign.attachment.intro') }}</p>
                    
                @include('campaigns._attachment')
            </div>
            
            @include('campaigns._preheader')

            {{-- @include('campaigns._campaign_header') --}}
        </div>
    </div>
        
    <hr>
    <div class="text-center">
        @if ($campaign->isABTest())
            <a href="{{ action('CampaignController@abSplitOptions', ['uid' => $campaign->uid]) }}"
                class="btn btn-primary px-4 py-2" style="border-radius:100px;"
            >
                {{ trans('messages.next') }} <span class="material-symbols-rounded">arrow_forward</span>
            </a>
        @else
            <a href="{{ action('CampaignController@schedule', ['uid' => $campaign->uid]) }}"
                class="btn btn-primary px-4 py-2" style="border-radius:100px;"
            >
                {{ trans('messages.next') }} <span class="material-symbols-rounded">arrow_forward</span>
            </a>
        @endif
            
    </div>
        
    <script>
        var templatePopup = new Popup();        
    
        $(document).ready(function() {
            $('.template-start').click(function() {
                var url = $(this).attr('data-url');
                
                templatePopup.load(url);
            });

            $('.template-compose').click(function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');

                openBuilder(url);
            });
            
            $('.template-compose-classic').click(function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');

                openBuilderClassic(url);
            });
        });

        $('#calculate-score').click(function() {
            spamPopup = new Popup({
                url: "{{ action('CampaignController@spamScore', ['uid' => $campaign->uid]) }}",
                fail: function(jqXHR) {
                    new Dialog('alert', {
                        message: JSON.parse(jqXHR.responseText).error,
                        ok: function() {
                            spamPopup.hide();
                        }
                    })
                }
            });
            spamPopup.load();
            return false;
        });
    </script>

@endsection
