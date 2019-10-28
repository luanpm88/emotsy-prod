@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
			<form id="trigger-select" action="{{ action("Automation2Controller@actionSelect") }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				
				<input type="hidden" name="type" value="{{ $type }}" />
				
				@if(View::exists('automation2.action.' . $type))
					@include('automation2.action.' . $type)
				@endif
				
				<hr>
				
				<button class="btn btn-mc_primary select-trigger-confirm"
					data-url="{{ action('Automation2Controller@triggerSelect', ['type' => $type]) }}"
				>
						{{ trans('messages.automation.trigger.select_confirm') }}
				</button>
			</form>
        </div>
    </div>
@endsection
