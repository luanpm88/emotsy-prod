<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.core._head')

        @include('layouts.core._script_vars')

        <!-- Theme -->
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/theme/'.Acelle\Model\Setting::get('frontend_scheme').'.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;1,100;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

</head>
    <body class="bg-slate-800 theme-{{ Acelle\Model\Setting::get('frontend_scheme') }} login-page">

        <div class="container-fluid" style="padding-left:20px;padding-right:20px;">
            <div class="row justify-content-center">
                <div class=" col-md-6 col-lg-6 bg-white login-panel">  
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
                                <div class="row justify-content-end h-100"> 
                                    <div class="col-sm-12 col-md-11 col-lg-9 d-flex flex-column justify-content-between"> 
                                        <div class="lhead-area">
                                            <div class="pt-4 pt-4 pe-4 ps-4 login-header pb-0">
                                                <a class="main-logo-big" href="{{ action('HomeController@index') }}">
                                                    <img src="{{ asset('images/logo-emotsy.svg') }}" alt="">
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
                                        </div>
                                        
                                        <!-- Footer -->
                                        <div class="small">
                                            <div class="login-footer-toolbar pb-1 mt-5 d-flex justify-content-between">
                                                <a class="toolbar-item" href="https://emotsy.com/legal/">Terms & Condition</a>
                                                <a class="toolbar-item" href="https://emotsy.com/legal/">Privacy Policy</a>
                                                <div class="dropdown">
                                                    <a class=" toolbar-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        {{ Acelle\Model\Language::where('code', language_code())->first()->name }}
                                                    </a>

                                                    <ul class="dropdown-menu">
                                                        @foreach (Acelle\Model\Language::active()->get() as $language)
                                                            @if ($language->code != language_code()) 
                                                                <li><a class="dropdown-item" href="{{ action('AppController@setGuestLanguage', [
                                                                    'code' => $language->code,
                                                                ]) }}">{{ $language->name }}</a></li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div> 
                                            </div>
                                            <div class="login-footer py-3">
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
                <div class=" d-sm-none col-md-6 col-lg-6 d-flex flex-wrap"> 
                    <div class="login-brand-bg">
                        <div class="brand-img mb-4" style="padding-top: 200px;">
                            <img src="{{ asset('images/emotsy-mkt.svg') }}">
                        </div>
                        <div class="login-brand">
                            <h2>Emotsy</h2>
                            <div class="mt-3">Integrating AI real-time personalization to elevate email marketing campaigns to new levels of effectiveness and engagement.</div>
                        </div>
                    </div> 
                </div>
            </div>
        </div> 

        {!! \Acelle\Model\Setting::get('custom_script') !!}

    </body>
</html>
