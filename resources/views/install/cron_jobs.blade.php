@extends('layouts.core.install')

@section('title', trans('messages.cron_jobs'))

@section('content')
	<form action="{{ action('InstallController@cronJobs') }}" method="POST" class="form-validate-jqueryz">
		{!! csrf_field() !!}
		
		@include('elements._cron_jobs')
    
		<hr>
		<div class="text-end">
			@if($valid)
				<a href="{{ action('InstallController@cronJobs') }}" class="btn btn-secondary">
					<span class="material-icons-outlined">
settings
</span> {!! trans('messages.change_cronjob_setting') !!}
				</a>
				<a href="{{ action('InstallController@finishing') }}" class="btn btn-primary">
					{!! trans('messages.next') !!} <span class="material-icons-round">
east
</span>
				</a>				
			@else
				<button type="submit" class="btn btn-primary bg-teal">
					{!! trans('messages.check_and_save_crontab') !!}
				</button>
			@endif
		</div>
	</form>
@endsection
