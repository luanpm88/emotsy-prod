@extends('layouts.core.login')

@section('title', trans('messages.not_authorized'))

@section('content')
    <div class="alert bg-danger alert-styled-left">
        <span class="text-semibold">
            {{ trans('messages.no_primary_payment') }}
        </span>
    </div>
    <a href='{{ action('Admin\PaymentController@index') }}' onclick='history.back()' class='btn btn-outline-secondary-400'>{{ trans('messages.go_to_admin_dashboard') }}</a>
@endsection