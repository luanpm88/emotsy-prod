@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
			<form id="action-select" action="{{ action("Automation2Controller@actionSelect") }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				
				<input type="hidden" name="type" value="{{ $type }}" />
				
				@if(View::exists('automation2.action.' . $type))
					@include('automation2.action.' . $type)
				@endif
				
				<hr>
				
				<button class="btn btn-secondary select-action-confirm"
					data-url="{{ action('Automation2Controller@actionSelect', ['type' => $type]) }}"
				>
						{{ trans('messages.automation.trigger.select_confirm') }}
				</button>
			</form>
        </div>
    </div>
@endsection
