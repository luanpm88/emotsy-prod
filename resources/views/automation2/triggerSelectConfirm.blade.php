@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3 class="mb-3">
                {{ trans('messages.automation.trigger.' . $key) }}
            </h3>
            <p class="mb-10">
                {{ trans('messages.automation.trigger.' . $key . '.intro') }}
            </p>
				
			<form id="trigger-select" action="{{ action("Automation2Controller@triggerSelect", $automation->uid) }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				
				<input type="hidden" name="options[key]" value="{{ $key }}" />
				
				@if(View::exists('automation2.trigger.' . $key))
					@include('automation2.trigger.' . $key)
				@endif
				
				<button class="btn btn-secondary select-trigger-confirm mt-2"
					data-url="{{ action('Automation2Controller@triggerSelect', ['uid' => $automation->uid]) }}"
				>
						{{ trans('messages.automation.trigger.select_confirm') }}
				</button>
			</form>
        </div>
    </div>
@endsection
