@extends('layouts.core.login')

@section('title', trans('messages.not_authorized'))

@section('content')
    <div class="alert alert-danger alert-styled-left">
        <span class="text-semibold">
            {{ trans('messages.plan.feature_limit') }}
        </span>
    </div>
    <a href='#back' onclick='history.back();return false;' class='btn btn-secondary'>{{ trans('messages.go_back') }}</a>
    <a href='{{ action('SubscriptionController@index') }}' class='btn btn-default'>
        <span class="material-symbols-rounded me-1">assignment</span> {{ trans('messages.subscriptions') }}</a>
@endsection