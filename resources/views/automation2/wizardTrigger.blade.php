@extends('layouts.popup.large')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mb-3">{{ trans('messages.automation.automation_trigger') }}</h2>
            <p>{{ trans('messages.automation.trigger.intro') }}</p>
                
            <div class="box-list mt-3">
				<div class="box-list mt-5">
					@foreach ($types as $type)
						<a class="box-item trigger-select-but trigger-{{ $type }} shadow-sm"
							data-key="{{ $type }}"	
							href="{{ action('Automation2Controller@wizardTriggerOption', [
								'trigger_type' => $type,
							]) }}"					
						>							
							<h6 class="d-flex align-items-center text-center justify-content-center">
								<i class="material-icons-outlined me-2">{{ trans('messages.automation.trigger.icon.' . $type) }}</i>
								<span>{{ trans('messages.automation.trigger.' . $type) }}</span></h6>
							<p>{{ trans('messages.automation.trigger.' . $type . '.desc') }}</p>
						</a>
					@endforeach                
            </div>
        </div>
    </div>

	<script>
		$(function() {
			$('.trigger-select-but').on('click', function(e) {
				e.preventDefault();
				var url = $(this).attr('href');
				
				createAutomationPopup.load(url);
			});
		});
	</script>
@endsection
