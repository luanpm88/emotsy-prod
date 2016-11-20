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
                <form action="" method="POST" class="form-validate-jqueryz tabable-select-box">
					<div class="row">
						<div class="col-md-12 tabable-select-radio">
							<h3 class="mt-0">{{ trans('messages.How_to_trigger_automation') }}</h3>
							@include('helpers.form_control', [
								'type' => 'radio',
								'name' => 'type',
								'class' => '',
								'label' => '',
								'value' => '',
								'options' => [
									['value' => 'specific-date', 'text' => trans('messages.Immediately_or_on_a_specific_date')],
									['value' => 'annual-recurring', 'text' => trans('messages.Annual_recurring')],
									['value' => 'list-subscription', 'text' => trans('messages.Event_based_list_subscription')],
									['value' => 'list-unsubscription', 'text' => trans('messages.Event_based_list_unsubscription')],
									['value' => 'event-based', 'text' => trans('messages.Event_based_custom_list_subscriber_event')],
									['value' => 'custom-criteria', 'text' => trans('messages.Custom_criteria')],
									['value' => 'api-call', 'text' => trans('messages.API_call')],
								],
								'rules' => []
							])
						</div>
					</div>
					<hr />
					<div class="tabable-container">
						<div class="row tabable-tab specific-date">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										@include('helpers.form_control', [
											'type' => 'date',
											'class' => '_from_now',
											'name' => 'send_on',
											'label' => trans('messages.Send_on'),
											'value' => '',
											'rules' => [],
											'help_class' => 'trigger'
										])
									</div>
									<div class="col-md-6 segments-select-box">
										@include('helpers.form_control', [
											'type' => 'time',
											'name' => 'send_at',
											'label' => trans('messages.At'),
											'value' => '',
											'rules' => [],
											'help_class' => 'trigger'
										])
									</div>
								</div>
							</div>
						</div>
						<div class="row tabable-tab annual-recurring">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day" class="styled">
												{{ trans('messages.On_specific_date') }}
											</label>
										</div>
										@include('helpers.form_control', [
											'type' => 'date',
											'class' => '_from_now',
											'name' => 'specific_date',
											'label' => '',
											'value' => '',
											'rules' => [],
											'help_class' => 'trigger'
										])
									</div>
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day-of-week" class="styled">
												{{ trans('messages.Day_of_the_week') }}
											</label>
										</div>
										<div class="row">
											<div class="col-md-6">
												@include('helpers.form_control', [
													'type' => 'select',
													'name' => 'type',
													'class' => '',
													'label' => '',
													'value' => '',
													'options' => Acelle\Library\Tool::dayOfWeekSelectOptions(),
													'rules' => []
												])
											</div>
											<div class="col-md-6">
												@include('helpers.form_control', [
													'type' => 'select',
													'name' => 'type',
													'multiple' => '',
													'label' => '',
													'value' => '',
													'include_blank' => trans('messages.Every_month'),
													'options' => Acelle\Library\Tool::monthSelectOptions(),
													'rules' => []
												])
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												@include('helpers.form_control', [
													'type' => 'select',
													'name' => 'type',
													'multiple' => '',
													'label' => '',
													'value' => '',
													'options' => [
														['value' => 'specific-date', 'text' => trans('messages.Only_1st_week_of_month')],
														['value' => 'annual-recurring', 'text' => trans('messages.Every_week')],
													],
													'rules' => []
												])
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day-of-week" class="styled">
												{{ trans('messages.Day_of_the_month') }}
											</label>
										</div>
										<div class="row">
											<div class="col-md-6">
												@include('helpers.form_control', [
													'type' => 'select',
													'name' => 'type',
													'class' => '',
													'label' => '',
													'value' => '',
													'options' => Acelle\Library\Tool::dayOfWeekSelectOptions(),
													'rules' => []
												])
											</div>
											<div class="col-md-6">
												@include('helpers.form_control', [
													'type' => 'select',
													'name' => 'type',
													'multiple' => '',
													'label' => '',
													'value' => '',
													'include_blank' => trans('messages.Every_month'),
													'options' => Acelle\Library\Tool::monthSelectOptions(),
													'rules' => []
												])
											</div>
										</div>										
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
