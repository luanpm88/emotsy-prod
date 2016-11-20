@extends('layouts.frontend')

@section('title', trans('messages.Automation') . " - " . trans('messages.recipients'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
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
						<div class="col-md-12">
							<h3 class="mt-0">{{ trans('messages.How_to_trigger_automation') }}</h3>
							@include('helpers.form_control', [
								'type' => 'radio',
								'name' => 'type',
								'class' => 'tabable-select',
								'label' => '',
								'value' => '',
								'options' => [
									['value' => '1', 'text' => trans('messages.Immediately_or_on_a_specific_date')],
									['value' => '2', 'text' => trans('messages.Annual_recurring')],
									['value' => '3', 'text' => trans('messages.Event_based_list_subscription')],
									['value' => '4', 'text' => trans('messages.Event_based_list_unsubscription')],
									['value' => '5', 'text' => trans('messages.Event_based_custom_list_subscriber_evennt')],
									['value' => '6', 'text' => trans('messages.Custom_criteria')],
									['value' => '7', 'text' => trans('messages.API_call')],
								],
								'rules' => []
							])
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6 tabable-container">
							<div class="tabable-tab-1">
								<div class="row">
									<div class="col-md-6 list_select_box" target-box="segments-select-box" segments-url="{{ action('SegmentController@selectBox') }}">
										@include('helpers.form_control', [
											'type' => 'date',
											'class' => '_from_now',
											'name' => 'delivery_date',
											'label' => trans('messages.delivery_date'),
											'value' => '',
											'rules' => [],
											'help_class' => 'campaign'
										])
									</div>
									<div class="col-md-6 segments-select-box">
										@include('helpers.form_control', [
											'type' => 'time',
											'name' => 'delivery_time',
											'label' => trans('messages.delivery_time'),
											'value' => '',
											'rules' => [],
											'help_class' => 'campaign'
										])
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<hr>
					<div class="">
						<button class="btn bg-teal-800">{{ trans('messages.Save_and_Next') }} <i class="icon-arrow-right7"></i> </button>
					</div>
					
				<form>
					
				<script>
                    $(document).ready(function() {
                        // Pick a day from now
                        // Date limits
                        $('.pickadate_from_now').pickadate({
                            min: [{{ Acelle\Library\Tool::dateTime(Carbon\Carbon::now())->year }},
                                    {{ Acelle\Library\Tool::dateTime(Carbon\Carbon::now()->subMonth(1))->month }},
                                    {{ Acelle\Library\Tool::dateTime(Carbon\Carbon::now())->day }}],
                            format: LANG_DATE_FORMAT
                        });
                    });
                </script>
@endsection
