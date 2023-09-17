@extends('layouts.core.backend', [
	'menu' => 'user',
])

@section('title', trans('messages.create_customer'))
	
@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\UserController@index", [
                'customer_uid' => $customer->uid,
            ]) }}">{{ trans('messages.users') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">add</span> {{ trans('messages.user.add_new') }}</span>
        </h1>
    </div>
@endsection

@section('content')
    @include('admin.customers._tabs', [
        'menu' => 'users',
    ])

    <form enctype="multipart/form-data" action="{{ action('Admin\UserController@store', [
        'customer_uid' => $customer->uid,
    ]) }}" method="POST">
        {{ csrf_field() }}
        
        @include('admin.users._form')
    <form>
@endsection
