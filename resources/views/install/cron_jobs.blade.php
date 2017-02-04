@extends('layouts.install')

@section('title', trans('messages.cron_jobs'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')
	<form action="{{ action('InstallController@cronJobs') }}" method="POST" class="form-validate-jqueryz">
		{!! csrf_field() !!}
		
		@include('elements._cron_jobs')
    
		<br />
		<div class="text-right">                                    
			<button class="btn btn-primary bg-teal">
				{!! trans('messages.next') !!} <i class="icon-arrow-right14 position-right"></i>
			</button>
		</div>
	</form>
@endsection
