@extends('layouts.core.backend')

@section('title', $language->name)
    
@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/ace.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/theme-twilight.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/mode-php.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/ace/mode-yaml.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/ace/jquery-ace.min.js') }}"></script>
@endsection

@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-share2"></i> {{ $language->name }}</span>
        </h1>
    </div>
                
@endsection

@section('content')
    
    <form enctype="multipart/form-data" action="{{ action('Admin\LanguageController@translate', ["id" => $language->uid, "file_id" => $currentFile['id']]) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}

        <input type="hidden" name="file_id" value="{{ $currentFile['id'] }}" />
        
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-top nav-underline">
                @foreach ($language->getLanguageFilesByType('default') as $langFile)
                    <li class="nav-item {{ $langFile['id'] == $currentFile['id'] ? "active" : "" }} text-semibold">
                        <a class="nav-link level-1" href="{{ action('Admin\LanguageController@translate', ["id" => $language->uid, "file_id" => $langFile['id']]) }}">
                            <i class="icon-menu6"></i> {{ $langFile['file_title'] }}
                        </a>
                    </li>
                @endforeach
                @if (count($language->getLanguageFilesByType('plugin')))
                    <li rel0="SubscriberController" class="nav-item">
                        <a href="https://acelle.com/account/contact" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            {{ trans('messages.language.plugins') }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach ($language->getLanguageFilesByType('plugin') as $langFile)
                                <li class="sub-menu text-semibold">
                                    <a class="dropdown-item {{ $langFile['id'] == $currentFile['id'] ? "active" : "" }}" href="{{ action('Admin\LanguageController@translate', ["id" => $language->uid, "file_id" => $langFile['id']]) }}">
                                        <i class="icon-menu6"></i> {{ $langFile['file_title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
                
            <div class="tab-content">
                <div class="tab-pane active" id="top-tab1">
                    <textarea name="content" class="my-code-messages" rows="20" style="width: 100%">{!! $content !!}</textarea>
                </div>                            
            </div>
        </div>
        
        <hr>
        <div class="text-end">
            <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\LanguageController@index') }}" role="button" class="btn btn-secondary">
                <i class="icon-cross2"></i> {{ trans('messages.cancel') }}
            </a>
        </div>
        
    <form>
    
    <script>
        $('.my-code-messages').ace({ theme: 'twilight', lang: 'yaml' });
        $('.sub-menu.active').each(function() {
            if ($(this).closest('li.dropdown').length) {
                $(this).closest('li.dropdown').addClass('active');
            }
        });
    </script>
    
@endsection