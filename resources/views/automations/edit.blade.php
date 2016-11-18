@extends('layouts.frontend')

@section('title', trans('messages.Create_automation'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("AutomationController@index") }}">{{ trans('messages.Automations') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-alarm-check"></i> {{ $automation->name }}</span>
				</h1>
                    
                @include('automations._steps', [
					'step' => 'recipients'
				])
			</div>
@endsection

@section('content')
                <form action="{{ action("AutomationController@update", $automation->uid) }}" method="POST" class="form-validate-jqueryz">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PATCH">
                    
					@include("automations._form")
					
					<hr>
					<div class="">
						<button class="btn bg-teal-800">{{ trans('messages.Save_and_Next') }} <i class="icon-arrow-right7"></i> </button>
					</div>
					
				<form>
@endsection
