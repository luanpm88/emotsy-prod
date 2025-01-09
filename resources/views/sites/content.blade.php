@extends('layouts.core.frontend', [
    'menu' => 'site',
])

@section('title', trans('messages.site.sites') . " - " . trans('messages.template'))
    
@section('head')      
    <script type="text/javascript" src="{{ AppUrl::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/editor.js') }}"></script>

    <!-- Dropzone -->    
	<script type="text/javascript" src="{{ AppUrl::asset('core/dropzone/dropzone.js') }}"></script>
    @include('helpers._dropzone_lang')
	<link href="{{ AppUrl::asset('core/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SiteController@index") }}">{{ trans('messages.site.sites') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded me-2">forward_to_inbox</span> {{ $site->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    @include('sites._menu', [
		'menu' => 'content',
	])

    <div class="row">
        <div class="col-md-10 mb-0">
            
            <div class="sub-section d-flex">
                <div class=" mr-auto pr-2">         
                    <h2 class="mt-0 mb-3">{{ trans('messages.site.content_management') }}</h2>           
                    <p>{{ trans('messages.site.content.intro') }}</p>
                        
                    <div class="media-left">
                        <div class="main">
                            <label>{{ trans('messages.site.content') }}</label>
                            <p>{{ trans('messages.site.content.last_edit', [
                                'time' => Auth::user()->customer->formatDateTime($site->updated_at, 'datetime_full'),
                            ]) }}</p>

                            <p class="mt-20">
                                <a href="{{ action('SiteController@builder', $site->uid) }}" class="btn btn-primary me-1 template-compose">
                                    {{ trans('messages.site.page_builder') }}
                                </a>
                                <a href="{{ action('SiteController@selectTemplate', $site->uid) }}" class="btn btn-link bg-grey-600 me-1">
                                    {{ trans('messages.site.change_template') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="template-thumb-container ml-4">
                        <img class="automation-template-thumb" src="{{ $site->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                        <a
                            onclick="popupwindow('{{ action('SiteController@preview', $site->uid) }}', `{{ $site->name }}`, 800)"
                            href="javascript:;"
                            class="btn btn-primary" style="display:none"
                        >
                            {{ trans('messages.site.preview') }}
                        </a>                           
                    </div>
                </div>
            </div>
        </div>
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
    </script>

@endsection
