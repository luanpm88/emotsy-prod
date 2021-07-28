@extends('layouts.core.frontend')

@section('title', trans('messages.' . $server->type))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection


@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-icons-outlined">
edit
</span> {{ trans('messages.' . $server->type) }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <p>{{ trans('messages.sending_server.wording') }}</p>

    <form enctype="multipart/form-data" action="{{ action('SendingServerController@update', ["id" => $server->uid, "type" => request()->type]) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">

        @include('sending_servers._form')
    <form>
@endsection
