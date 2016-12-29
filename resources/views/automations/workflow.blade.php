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
					<li><a href="{{ action("AutomationController@index") }}">{{ trans('messages.automations') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-alarm-check"></i> {{ $automation->name }}</span>
				</h1>
					
				@include('automations._steps', [
					'step' => 'workflow'
				])
			</div>
@endsection

@section('content')
                    <!-- Timeline -->
					<div class="addable-multiple-form">
						<div class="timeline timeline-left content-group">
							<div class="timeline-container">				
								<!-- Sales stats -->
								<div class="timeline-row">
									<div class="timeline-icon timeline-icon-i auto-event-line">
                                        <!--<i class="icon-paperplane text-teal"></i>-->
										1
									</div>
		
									<div class="panel panel-flat timeline-content">
										<div class="panel-heading">
											<h6 class="panel-title text-semibold">
												{{ trans('messages.trigger_workflow_when_the_following_conditions_are_met') }}
											</h6>
											<div class="heading-elements">
												<span class="text-muted2 list-status">
													<span class="label label-flat bg-{{ $first_event->status }}">{{ $first_event->status }}</span>
												</span>
											</div>
										</div>
		
										<div class="panel-body">
											<span class="status-mark border-success position-left"></span>
											{!! $first_event->displayMessage() !!}
										</div>
									</div>
									
									<div class="event-campaigns-box">
										<div class="event-campaigns-container" data-url="{{ action('AutoEventController@campaigns', $first_event->uid) }}">
											
										</div>
											
										<div class="text-center">
											@if (isset($first_event->id))
												<button class="btn btn-xs bg-teal event-campaign-add" data-url="{{ action('AutoEventController@addCampaign', $first_event->uid) }}">
													<i class="icon-plus2"></i> {{ trans('messages.add_email') }}
												</button>
											@else
												<a class="btn btn-xs bg-teal" href="{{ action('AutomationController@trigger', $automation->uid) }}">
													<i class="icon-alignment-align"></i> {{ trans('messages.set_trigger') }}
												</a>
											@endif
										</div>
									</div>
									
									<br /><br />
									
								</div>
								<!-- /sales stats -->
								
								<div class="addable-multiple-container">
									@foreach ($automation->getFollowUpEvents() as $key =>  $auto_event)
										@include("auto_events.show")
									@endforeach
								</div>
									
							</div>
						</div>
						<br />
						<a sample-url="{{ action('AutomationController@nextEventForm', $automation->uid) }}" href="#add_condition" class="btn btn-info bg-info-800 add-form">
							<i class="icon-plus2"></i> {{ trans('messages.add_event') }}
						</a>
					</div>
                    
					<hr>
					<div class="text-right">
						<a href="{{ action('AutomationController@confirm', $automation->uid) }}" class="btn bg-teal-800">
							{{ trans('messages.review_and_confirm') }} <i class="icon-arrow-right7"></i>
						</a>
					</div>
					
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
							
							// update auto event number
							setInterval('updateAutoEventNumber()', 1000);
						});
					</script>
@endsection
