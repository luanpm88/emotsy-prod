@extends('layouts.frontend')

@section('title', trans('messages.Automation') . " - " . trans('messages.recipients'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-alarm-check"></i> {{ $automation->name }}</span>
				</h1>
					
				@include('automations._steps', [
					'step' => 'trigger'
				])
			</div>
@endsection

@section('content')
                <form action="" method="POST" class="form-validate-jqueryz">
					<div class="row">
						<div class="col-md-6">
							<h3 class="mt-0">{{ trans('messages.How_to_trigger_automation') }}</h3>
							@include('helpers.form_control', [
								'type' => 'radio',
								'name' => 'name',
								'label' => '',
								'value' => '',
								'options' => [
									['value' => '', 'text' => trans('messages.Immediately_or_on_a_specific_date')],
									['value' => '', 'text' => trans('messages.Annual_recurring')],
									['value' => '', 'text' => trans('messages.Event_based_list_subscription')],
									['value' => '', 'text' => trans('messages.Event_based_list_unsubscription')],
									['value' => '', 'text' => trans('messages.Event_based_custom_list_subscriber_evennt')],
									['value' => '', 'text' => trans('messages.Custom_criteria')],
									['value' => '', 'text' => trans('messages.API_call')],
								],
								'rules' => []
							])
						</div>
					</div>
					
					<hr>
					<div class="">
						<button class="btn bg-teal-800">{{ trans('messages.Save_and_Next') }} <i class="icon-arrow-right7"></i> </button>
					</div>
					
				<form>
					
				
@endsection
