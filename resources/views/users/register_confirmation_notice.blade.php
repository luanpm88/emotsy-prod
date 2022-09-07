@extends('layouts.core.register')

@section('title', trans('messages.create_your_account'))

@section('content')

    <div class="row mt-5">
        <div class="col-md-1"></div>
        <div class="col-md-3 text-end">
            <a class="main-logo-big" href="{{ action('HomeController@index') }}">
                <img width="150px" src="{{ getSiteLogoUrl('dark') }}" alt="">
            </a>
        </div>
        <div class="col-md-6">
            
            <h1 class="mb-10">{{ trans('messages.email_confirmation') }}</h1>
            <p>{!! trans('messages.activation_email_sent_content') !!}</p>
                
        </div>
        <div class="col-md-1"></div>
    </div>
@endsection
