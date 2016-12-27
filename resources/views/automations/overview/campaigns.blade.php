@extends('layouts.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.confirm'))
	
@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("AutomationController@index") }}">{{ trans('messages.automations') }}</a></li>
					<li>{{ trans('messages.overview') }}</li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-alarm-check"></i> {{ $automation->name }}</span>
				</h1>
					
				@include('automations.overview._menu', [
					'step' => 'campaigns'
				])
			</div>
@endsection

@section('content')
    
        
@endsection
