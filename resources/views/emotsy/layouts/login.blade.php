<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.core._head')

        @include('layouts.core._script_vars')

        <!-- Theme -->
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/theme/'.Acelle\Model\Setting::get('frontend_scheme').'.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,100;9..40,700&display=swap" rel="stylesheet"> 
<style>
body{
    padding: 0;
    background-color: #F2FEFF;
}
.theme-emotsy.login-page{
    background-color: #F2FEFF;
}
.theme-emotsy.bg-white{
    background-color: #ffffff;
}
.theme-emotsy.login-page .panel-body{
    box-shadow: none !important;
}
.theme-emotsy.login-page .panel-body h4{
    color: #222751;
    font-size: 20px !important;
    font-family: DM Sans;
    font-weight: 700;
}
.login-description{
    color: #9DA0B7;
    font-family: DM Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 23.1px;
}
.has-account{
    color: #9DA0B7;
    font-family: DM Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 23.1px; /* 165% */ 
}
.cretenew{
    color: #00A3B9 !important;
    font-family: DM Sans;
    font-size: 14px; 
    font-weight: 600;
    line-height: 23.1px; 
    text-decoration-line: underline; 
}
.emailoraccount{
    color: #344357;
    font-family: DM Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: 23.1px; /* 165% */ 
}
.login-password-input::before, .login-email-input::before{
    content:'';
}
.login-brand-bg{

}
.login-brand-bg img{
    width: 100%;
    height: auto;
}
</style>
</head>
    <body class="bg-slate-800 theme-{{ Acelle\Model\Setting::get('frontend_scheme') }} login-page">

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-6 col-lg-6 bg-white vh-100 "> 

                    
        <!-- Page container -->
        <div class="page-container login-container">
            @if (\Auth::check())
                <div class="text-end">
                    <a href="{{ url("/logout") }}"  class='text-white ml-4'><i class="icon-switch2"></i> {{ trans('messages.logout') }}</a>
                </div>
            @endif

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper" style="padding-left:20px;padding-right:20px;">
                    <div class="row justify-content-end"> 
                        <div class="col-sm-8 col-md-8 col-lg-8">

                            <div class="text-center login-header">
                                <a class="main-logo-big" href="{{ action('HomeController@index') }}">
                                    <img src="{{ asset('images/logo-dark.png') }}" alt="">
                                </a>
                            </div>

                            <!-- display flash message -->
		                    @foreach (['danger', 'warning', 'info', 'error'] as $msg)
                                @php
                                    $class = $msg;
                                    if ($msg == 'error') {
                                        $class = 'danger';
                                    }
                                @endphp
                                @if(Session::has('alert-' . $msg))
                                    <!-- Form Error List -->
                                    <div class="alert alert-{{ $class }} alert-noborder alert-dismissible">
                                        <strong>{{ trans('messages.' . $msg) }}</strong>

                                        <br>

                                        <p>{!! preg_replace('/[\r\n]+/', ' ', Session::get('alert-' . $msg)) !!}</p>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif    
                            @endforeach
                            
                            @yield('content')
                            
                             <!-- Footer -->
                            <div class="small">
                                <div class="footer text-white py-3" style="width: 100%">
                                    {!! trans('messages.brand.copyright', ['app_name' => get_app_name()] ) !!}
                                </div>
                            </div>
                            <!-- /footer -->

                        </div>
                    </div>
                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->


           

        </div>
        <!-- /page container -->



                </div>
                <div class="col-6 col-lg-6 d-flex align-content-center flex-wrap"> 
                    <div class="login-brand-bg">
                        <img src="{{ asset('images/emotsy-mkt.png') }}">
                    </div>
                </div>
            </div>
        </div>
        



        {!! \Acelle\Model\Setting::get('custom_script') !!}

    </body>
</html>
