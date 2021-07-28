@extends('layouts.core.frontend')

@section('title', trans('messages.create_sending_server'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection


@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-plus-circle2"></i> {{ trans('messages.create_sending_server') }}</span>
        </h1>
    </div>

@endsection

@section('content')
	<p>{{ trans('messages.sending_server.wording') }}</p>

    <form action="{{ action('SendingServerController@store', ["type" => request()->type]) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}

        @include('sending_servers._form')
    <form>

@endsection
