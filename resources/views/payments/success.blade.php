@extends('layouts.frontend')

@section('title', trans('messages.subscription'))

@section('page_script')    
@endsection

@section('page_header')

	<div class="page-title">				
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="icon-quill4"></i> {{ trans('messages.your_subscriptions') }}</span>
		</h1>				
	</div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panelz">
                <div class="panel-bodyz">
                    {!! trans('messages.subscription_paid_finish_messages', ['plan' => $subscription->plan_name]) !!}
                </div>
            </div>
                
            <br />
            <div class="text-left">
                <a href="{{ action('HomeController@index') }}" class="btn bg-teal"><i class="icon-home"></i> {{ trans('messages.go_to_dashboard') }}</a>
                <a href="{{ action('SubscriptionController@index') }}" class="btn bg-grey"><i class="icon-quill4"></i> {{ trans('messages.check_your_subscriptions') }}</a>
            </div>
        </div>
    </div>
@endsection
