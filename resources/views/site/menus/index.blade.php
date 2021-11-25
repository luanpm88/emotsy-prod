@extends('layouts.core.frontend')

@section('title', trans('messages.create_admin'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>

    <script src="{{ URL::asset('core/js/UrlAutoFill.js') }}"></script>
@endsection
	
@section('page_header')
	
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Site\MenuController@index") }}">{{ trans('messages.site.menus') }}</a></li>
        </ul>
    </div>

@endsection

@section('content')
    <iframe scrolling="no" id="wpFrame" src="http://wp.vbrand.com/wp-admin/nav-menus.php?action=edit&menu=0" style="border:none;width:100%;overflow:hidden"></iframe>

    <script>
        window.addEventListener("message", (event) => {
            var height = event.data.height;

            console.log(height);

            var frame = window.document.getElementById('wpFrame');
            frame.style.height = height + 'px';

            // dark mode check
            document.getElementById('wpFrame').contentWindow.postMessage({
                dark_mode: $('body').hasClass('mode-dark')
            }, "*");
        }, false);
    </script>
@endsection
