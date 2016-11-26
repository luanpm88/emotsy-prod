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
						<!--<div class="row tabable-tab annual-recurring">
							@include('automations.triggers._annual-recurring')							
						</div>
						<div class="row tabable-tab event-based">
							@include('automations.triggers._event-based')							
						</div>
						<div class="row tabable-tab custom-criteria">
							@include('automations.triggers._custom-criteria')							
						</div>-->
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
