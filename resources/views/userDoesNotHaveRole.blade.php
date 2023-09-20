@extends('layouts.core.login')

@section('title', trans('messages.not_authorized'))

@section('content')
    <div class="alert alert-danger alert-styled-left">
        <span class="text-semibold">
            {{ trans('messages.user.does_not_have_role.error') }}
        </span>
    </div>
    <a href='#back' onclick='history.back();return false;' class='btn btn-secondary'>{{ trans('messages.go_back') }}</a>
@endsection