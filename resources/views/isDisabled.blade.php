@extends('layouts.core.login')

@section('title', trans('messages.not_authorized'))

@section('content')
    <div class="alert bg-danger alert-styled-left">
        <span class="text-semibold">
            {{ trans('messages.your_account_is_disabled') }}
        </span>
    </div>
    <a href='#back' onclick='history.back()' class='btn btn-outline-secondary-400'>{{ trans('messages.go_back') }}</a>
@endsection