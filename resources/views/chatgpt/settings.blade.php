@extends('layouts.core.frontend_no_subscription', [
	'menu' => 'chatgpt',
])

@section('title', trans('messages.account.chatgpt'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-address-book3"></i> {{ $customer->getName() }}</span>
        </h1>
    </div>

@endsection

@section('content')

    @include("account._menu", [
		'menu' => 'chatgpt',
	])

    <div class="mb-4">
        <div style="">
            <img src="{{ url('images/chatgpt_banner.svg') }}" width="80px" />
        </div>
    </div>
    
    <h2 class="">{{ trans('chatgpt.connection') }}</h2>

    <div class="row">
        <div class="col-md-10">
            <p>
                {!! trans('chatgpt.settings.intro') !!}
            </p>
        </div>
    </div>

    <form enctype="multipart/form-data" action="{{ action('\Acelle\Http\Controllers\ChatgptController@settings') }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('openai_api_key') ? 'has-error' : '' }}">
                    <label for="">{{ trans('chatgpt.openai_api_key') }}</label>
                    <input
                        value="{{ Auth::user()->customer->getChatgptApiKey() }}"
                        type="password"
                        name="openai_api_key"
                        class="form-control required"
                    />
    
                    @if ($errors->has('openai_api_key'))
                        <span class="help-block">
                            {{ $errors->first('openai_api_key') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-left">
            <button class="btn btn-secondary me-1">{{ trans('messages.save') }}</button>
            <a class="btn btn-light" href="{{ action('Admin\PluginController@index') }}">{{ trans('chatgpt.cancel') }}</a>
        </div>

    </form>
       
@endsection