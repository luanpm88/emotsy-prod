@extends('layouts.frontend')

@section('title', trans('messages.Automation') . " - " . trans('messages.recipients'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')	
			<div class="page-title">
				@include('automations._head')
					
				@include('automations._steps', [
					'step' => 'trigger'
				])
			</div>
@endsection

@section('content')
                <form action="" method="POST" class="form-validate-jqueryz tabable-select-box">
					{{ csrf_field() }}
					
					<div class="row">
						<div class="col-md-12 tabable-select-radio">
							<h3 class="mt-0">{{ trans('messages.How_to_trigger_automation') }}</h3>
							@include('helpers.form_control', [
								'type' => 'radio',
								'name' => 'event_type',
								'class' => '',
								'label' => '',
								'value' => $first_event->event_type,
								'options' => Acelle\Model\AutoEvent::typeNameSelectOptions(),
								'rules' => []
							])
						</div>
					</div>
					
					<div class="tabable-container">
						<div class="row tabable-tab specific-date">
							@include('automations.triggers._specific-date')
						</div>
						<div class="row tabable-tab weekly-recurring">
							@include('automations.triggers._weekly-recurring')							
						</div>
						<div class="row tabable-tab monthly-recurring">
							@include('automations.triggers._monthly-recurring')							
						</div>
						<div class="row tabable-tab subscriber-event">
							@include('automations.triggers._subscriber-event')							
						</div>
						<div class="row tabable-tab custom-criteria">
							@include('automations.triggers._custom-criteria')							
						</div>
						<div class="row tabable-tab custom-criteria subscriber-event list-subscription list-unsubscription">
							<div class="col-md-6">
								<hr />
								<h6 class="panel-title text-semibold auto-event-form-line">
									{{ trans('messages.wait') }}
									@include('helpers.form_control', [
										'type' => 'text',
										'name' => 'delay_value',
										'class' => 'numeric',
										'label' => '',
										'value' => null !== $first_event->getDataValue('delay_value') ? $first_event->getDataValue('delay_value') : '0',
										'rules' => []
									])
									@include('helpers.form_control', [
										'type' => 'select',
										'name' => 'delay_unit',
										'multiple' => '',
										'label' => '',
										'value' => null !== $first_event->getDataValue('delay_unit') ? $first_event->getDataValue('delay_unit') : 'day',
										'options' => Acelle\Model\AutoEvent::timeUnitOptions(),
										'rules' => []
									])
									@include('helpers.form_control', [
										'type' => 'select',
										'name' => 'delay_type',
										'multiple' => '',
										'label' => '',
										'value' => null !== $first_event->getDataValue('delay_type') ? $first_event->getDataValue('delay_type') : 'before',
										'options' => Acelle\Model\AutoEvent::delayTypeOptions(),
										'rules' => []
									])
									{{ trans('messages.trigger_occurred') }}
								</h6>
							</div>
						</div>
					</div>
				<hr>
					<div class="text-right">
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
