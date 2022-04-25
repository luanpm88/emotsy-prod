@extends('layouts.popup.large')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mb-3">{{ trans('messages.automation.automation_trigger') }}</h2>
            <p>{{ trans('messages.automation.trigger.intro') }}</p>
                
            <div class="box-list mt-3">
				<div class="box-list mt-5">
					@foreach ($types as $type)
						<a class="box-item trigger-select-but trigger-{{ $type }} shadow-sm
								{{ $trigger->getOption('key') == $type ? 'current' : '' }}
							"
							data-key="{{ $type }}"						
						>							
							<h6 class="d-flex align-items-center text-center justify-content-center">
								@php
									switch($type) {
										case 'welcome-new-subscriber':
											$icon = 'accessibility_new';
											break;
										case 'say-happy-birthday':
											$icon = 'cake';
											break;
										case 'subscriber-added-date':
											$icon = 'playlist_add';
											break;
										case 'specific-date':
											$icon = 'calendar_today';
											break;
										case 'say-goodbye-subscriber':
											$icon = 'launch';
											break;
										case 'api-3-0':
											$icon = 'code';
											break;
										case 'weekly-recurring':
											$icon = 'code';
											break;
										case 'monthly-recurring':
											$icon = 'date_range';
											break;
										case 'woo-abandoned-cart':
											$icon = '';
											break;
										default:
											$icon = 'restart_alt';
									}
								@endphp
								<i class="material-icons-outlined me-2">{{ $icon }}</i>
								<span>{{ trans('messages.automation.trigger.' . $type) }}</span></h6>
							<p>{{ trans('messages.automation.trigger.' . $type . '.desc') }}</p>
						</a>
					@endforeach                
            </div>
        </div>
    </div>
@endsection
