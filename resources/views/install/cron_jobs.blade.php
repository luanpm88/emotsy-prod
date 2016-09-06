@extends('layouts.install')

@section('title', trans('messages.cron_jobs'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')

	<h3 class="text-teal-800"><i class="icon-puzzle2"></i> {{ trans('messages.setting_up_cron_jobs') }}</h3>
    
    <div class="">
        Insert the following lines to your system contab. <br />
        Please note, below timings for running the cron jobs are the recommended, you can change it if you want.
    </div>
    <br/>
    <pre style="font-size: 16px"># Campaigns sender, runs each minute.
* * * * * {{ explode(" ", exec("whereis php"))[1]}} -q {{ base_path() }}/artisan handler:run 2&gt;&amp;1
* * * * * {{ explode(" ", exec("whereis php"))[1]}} -q {{ base_path() }}/artisan campaign:run 2&gt;&amp;1
    </pre>
                            
    
	<br />
	<div class="text-right">                                    
		<a href="{{ action('InstallController@finish') }}" class="btn btn-primary bg-teal">{!! trans('messages.finish') !!} <i class="icon-arrow-right14 position-right"></i></a>
	</div>

@endsection
