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
					'step' => 'workflow'
				])
			</div>
@endsection

@section('content')
    
        <div class="confirm-campaign-box">
                <form action="{{ action('AutomationController@confirm', $automation->uid) }}" method="POST" class="form-validate-jqueryz">
                    {{ csrf_field() }}
                    
                    <div class="head">
                        <h2 class="text-semibold mb-5">{{ trans('messages.workflow_overview') }}</h2>
                    </div>
                    
                    <ul class="modern-listing automation-confirm">
                        <li>
                            <h4>{{ trans('messages.recipients') }}</h4>
                            <p>
                                @if (is_object($automation->segment))
                                    {{ $automation->mailList->name }} . {{ $automation->segment->name }}: {{ $automation->subscribers()->count() }} {{ strtolower(trans('messages.recipients')) }}
                                @elseif (is_object($automation->mailList))
                                    {{ $automation->mailList->name }}: {{ $automation->subscribers()->count() }} {{ strtolower(trans('messages.recipients')) }}
                                @endif
                            </p>
                        </li>
						<li>                            
                            <h4><numbericon>1</numbericon> {{ trans('messages.trigger_workflow_when_the_following_conditions_are_met') }}</h4>
							<p>
								{!! $first_event->displayMessage() !!}
							</p>
                        </li>
						@if (isset($first_event->id))
                            @foreach($first_event->campaigns as $key => $campaign)
                                @include('automations.overview._campaign_row', ['event' => $first_event])
                            @endforeach
						@endif
						
						@foreach ($automation->getFollowUpEvents() as $key =>  $auto_event)
							<li>								
								<h4><numbericon>{{ $key+2 }}</numbericon>  {!! $auto_event->displayMessage() !!}</h4>
								<p>
									{{ trans('messages.follow_up') }}
								</p>
							</li>
							@foreach($auto_event->campaigns as $key => $campaign)
                                @include('automations.overview._campaign_row', ['event' => $auto_event])
                            @endforeach
						@endforeach
                    </ul>
                </form>
                    
            </div>
@endsection
