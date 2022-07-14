@extends('layouts.core.backend')

@section('title', trans('paddle::messages.paddle'))

@section('content')    
    <h3>{{ trans('messages.plugin.deleted', [
        'plugin' => $pluginName,
    ]) }}</h3>
    <p>
        {!! trans('messages.plugin.deleted.wording') !!}
    </p>

    <a href="{{ action('Admin\PluginController@index') }}" class="">{{ trans('messages.plugin.back_to_plugins') }}</a>
@endsection