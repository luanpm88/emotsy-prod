@extends('layouts.install')

@section('title', trans('messages.cron_jobs'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')


        <h3 class="text-teal-800"><i class="icon-checkmark4"></i> Congratulations, you've successfully installed Mailixa</h3>
            
        Remember that all your configurations were saved in <strong class="text-semibold">[APP_ROOT]/.env</strong> file. You can change it when needed.
        <br /><br />
        Now, you can go to your Admin Panel with link: <a class="text-semibold" href="{{ action('Admin\HomeController@index') }}">{{ action('Admin\HomeController@index') }}</a>.
        <br /><br />
        If you are having problems or suggestions, please visit <a class="text-semibold" href="http://www.mailixa.net" target="_blank">mailixa.net official website</a>.
        <br><br>

        Thank you for chosing Mailixa.
        <div class="clearfix"><!-- --></div>      
<br />

@endsection
