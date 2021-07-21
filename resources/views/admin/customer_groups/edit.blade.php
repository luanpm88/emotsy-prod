@extends('layouts.core.backend')

@section('title', $group->name)
    
@section('page_header')
    
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\CustomerGroupController@index") }}">{{ trans('messages.customer_groups') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-icons-outlined">
edit
</span> {{ $group->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
                
    <form action="{{ action('Admin\CustomerGroupController@update', $group->id) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">
        
        @include("admin.customer_groups._form")                
        <hr />
        <div class="text-left">
            <button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\CustomerGroupController@index') }}" class="btn btn-secondary-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
        </div>
        
    <form>
                    
                
@endsection
