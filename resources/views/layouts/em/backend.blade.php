<?php
$menu = $menu ?? '';
?>
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://athenaevs.com/products/sneat-bootstrap-html-admin-template/
* Created by: AthenaEVS
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright AthenaEVS (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ \Acelle\Model\Setting::get("site_description") }}">
    <meta name="keywords" content="{{ \Acelle\Model\Setting::get("site_keyword") }}" />
    <meta name="php-version" content="{{ phpversion() }}" />

    <title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

    @include('layouts.core._favicon')   

    <!-- Font -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Poppins:400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet"> --}}

    <!-- jQuery -->
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/jquery-3.6.4.min.js') }}"></script>

    <!-- Bootstrap -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/bootstrap/css/bootstrap.min.css') }}"> --}}
    <script type="text/javascript" src="{{ AppUrl::asset('core/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/select2/css/select2.min.css') }}">
    <script type="text/javascript" src="{{ AppUrl::asset('core/select2/js/select2.min.js') }}"></script>

    <!-- Validate -->
    <script type="text/javascript" src="{{ AppUrl::asset('core/validate/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/validate.js') }}"></script>
    <script type="text/javascript" src="{{ action('Controller@jquery_validate_locale') }}"></script>

    <!-- Numeric -->
    <script type="text/javascript" src="{{ AppUrl::asset('core/numeric/jquery.numeric.min.js') }}"></script>

    <!-- Tooltip -->
    <link rel="stylesheet" href="{{ AppUrl::asset('core/tooltipster/css/tooltipster.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ AppUrl::asset('core/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-light.min.css') }}">
    <script type="text/javascript" src="{{ AppUrl::asset('core/tooltipster/js/tooltipster.bundle.min.js') }}"></script>

    <!-- Google icon -->
    <link href="{{ AppUrl::asset('core/css/google-font-icon.css') }}?v=2" rel="stylesheet">

    <!-- Autofill -->
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/autofill.css') }}">
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/autofill.js') }}"></script>

    <!-- Theme -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/dark.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/menu.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/circle.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/app.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/responsive.css') }}">

    <!-- Custom css -->
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('custom.css') }}">

    <script type="text/javascript" src="{{ AppUrl::asset('core/js/functions.js') }}"></script>

    <script type="text/javascript" src="{{ AppUrl::asset('core/js/link.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/box.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/popup.js') }}?v=1"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/sidebar.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/list.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/anotify.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/dialog.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/iframe_modal.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/search.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/image_popup.js') }}"></script>

    <script type="text/javascript" src="{{ AppUrl::asset('core/js/app.js') }}"></script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });
            
    </script>

    @include('layouts.core._script_vars')

    @yield('head')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ AppUrl::asset('em/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ AppUrl::asset('em/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ AppUrl::asset('em/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ AppUrl::asset('em/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ AppUrl::asset('em/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ AppUrl::asset('em/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ AppUrl::asset('em/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ AppUrl::asset('em/assets/js/config.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('em/css/custom.css') }}?v=2">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

    <script>
      @if (Auth::user()->admin->theme_mode == 'auto')
        var ECHARTS_THEME = isDarkMode() ? 'dark' : null
  
        // auto detect dark-mode
        $(function() {
          autoDetechDarkMode('{{ action('AccountController@saveAutoThemeMode') }}');
        });
      @else
        var ECHARTS_THEME = '{{ Auth::user()->admin->theme_mode == 'dark' ? 'dark' : null }}';
      @endif
    </script>
  </head>

  
  <body class="body-em backend-em" data-bs-theme="light">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ asset('/') }}" class="app-brand-link">
              <span class="app-brand-logo demo" style="overflow:visible;">
                <img style="height:70px;margin-top:-20px;" class="logo" src="{{ getSiteLogoUrl('dark') }}" alt="">
              </span>
            </a>

            {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a> --}}
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item {{ $menu == 'dashboard' ? 'active' : '' }}">
              <a href="{{ action('Admin\HomeController@index') }}" class="menu-link">
                <img src="{{ asset('em/images/home.svg') }}" class="menu-icons" alt="">
                <div data-i18n="Analytics">{{ trans('messages.dashboard') }}</div>
              </a>
            </li>
            <li class="menu-item {{ $menu == 'customer' ? 'active' : '' }}">
              <a href="{{ action('Admin\CustomerController@index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Layouts">{{ trans('messages.customers') }}</div>
              </a>
            </li>  
            <li class="menu-item {{ $menu == 'subscription' ? 'active' : '' }}">
              <a href="{{ action('Admin\SubscriptionController@index') }}" class="menu-link"> 
                <img src="{{ asset('em/images/price.svg') }}" class="menu-icons" alt="">
                <div data-i18n="Misc">{{ trans('messages.subscriptions') }}</div>
              </a>
            </li>   
            <li class="menu-item {{ in_array($menu, ['plan']) ? 'active' : '' }}">
              <a href="{{ action('Admin\PlanController@index') }}" class="menu-link"> 
                <img src="{{ asset('em/images/test.svg') }}" class="menu-icons" alt="">
                <div data-i18n="Account Settings">{{ trans('messages.plans') }}</div>
              </a> 
            </li>
            <li class="menu-item {{ in_array($menu, ['email_verification_plan']) ? 'active' : '' }}">
              <a href="{{ action('Admin\EmailVerificationPlanController@index') }}" class="menu-link"> 
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Account Settings">{{ trans('messages.email_verification_plan.plans') }}</div>
              </a> 
            </li>
            <li class="menu-item {{ in_array($menu, ['setting']) ? 'active' : '' }}">
              <a href="{{ action('Admin\SettingController@index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Authentications">{{ trans('messages.settings') }}</div>
              </a>
            </li>
            
            <li class="menu-item {{ in_array($menu, ['currency']) ? 'active' : '' }}">
              <a href="{{ action('Admin\CurrencyController@index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Extended UI">{{ trans('messages.currencies') }}</div>
              </a> 
            </li>
            <li class="menu-item {{ in_array($menu, ['language']) ? 'active' : '' }}">
              <a href="{{ action('Admin\LanguageController@index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-flag"></i>
                <div data-i18n="Boxicons">{{ trans('messages.language') }}</div>
              </a>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav class="layout-navbar container-xxl align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="">
              <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                  <i class="bx bx-menu bx-sm"></i>
                </a>
              </div>

              <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <!-- Search -->
                <div class="navbar-nav align-items-center dashboard"> 
                    @hasSection('name')
                      @yield('page_title')
                    @else
                      Ethana EVS
                    @endif
                </div>
                <!-- /Search -->
                
                

                <ul class="navbar-nav flex-row align-items-center ms-auto">
                  <!-- Place this tag where you want the button to render. -->
                  <li class="nav-item lh-1 me-3">
                    <button class="btn btn-warning highContrastColor" class="zero-button yellow bigger" tabindex="0">{{ trans('athena.buy_credits') }}</button>
                  </li>
                  <li class="nav-item lh-1 me-3">
                    <a
                      class="github-button"
                      href="https://github.com/themeselection/sneat-html-admin-template-free"
                      data-icon="octicon-star"
                      data-size="large"
                      data-show-count="true"
                      aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                      >{{ trans('athena.Star') }}</a
                    >
                  </li>
                  
                  <!-- User -->
                  <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <img src="{{ Auth::user()->getProfileImageUrl() }}" alt class="w-px-40 h-auto rounded-circle" /> <i class='bx bx-chevron-down'></i>
                      </div>
                      
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="#">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                <img src="{{ Auth::user()->getProfileImageUrl() }}" alt class="w-px-40 h-auto rounded-circle" />
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="fw-semibold d-block">{{ Auth::user()->admin->displayName() }}</span>
                              <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                          </div>
                        </a>
                      </li>
                      @if (config('app.saas'))
                          @if (isSiteDemo()) 
                              <li>
                                  <a href="{{ action("Controller@demo") }}" class="dropdown-item d-flex align-items-center border-bottom">
                                      <i class="navbar-icon">
                                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 93.8 89" style="enable-background:new 0 0 93.8 89;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M59.2,89H18.4C8.3,89,0,79.6,0,68.1V20.8C0,9.3,8.3,0,18.4,0h40.8c9.4,0,17.3,8,18.3,18.7l-7,0.6 C69.9,12.3,65,7,59.2,7H18.4C12.1,7,7,13.2,7,20.8v47.3C7,75.8,12.1,82,18.4,82h40.8c5.8,0,10.7-5.3,11.3-12.4l7,0.6 C76.5,80.9,68.7,89,59.2,89z"/><rect x="40.6" y="41" class="st0" width="24.1" height="7"/><polygon class="st0" points="58.8,64.7 93.8,44.5 58.8,24.2 "/></g></g></svg>
                                      </i>{{ trans('messages.switch_view') }}
                                  </a>
                              </li>
                              <li class="divider"></li>
                          @else
                              @can("customer_access", Auth::user())
                                <li class="border-bottom"><a href="{{ action("HomeController@index") }}" class="dropdown-item d-flex align-items-center">
                                    <i class="navbar-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 93.8 89" style="enable-background:new 0 0 93.8 89;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M59.2,89H18.4C8.3,89,0,79.6,0,68.1V20.8C0,9.3,8.3,0,18.4,0h40.8c9.4,0,17.3,8,18.3,18.7l-7,0.6 C69.9,12.3,65,7,59.2,7H18.4C12.1,7,7,13.2,7,20.8v47.3C7,75.8,12.1,82,18.4,82h40.8c5.8,0,10.7-5.3,11.3-12.4l7,0.6 C76.5,80.9,68.7,89,59.2,89z"/><rect x="40.6" y="41" class="st0" width="24.1" height="7"/><polygon class="st0" points="58.8,64.7 93.8,44.5 58.8,24.2 "/></g></g></svg>
                                    </i>{{ trans('messages.customer_view') }}
                                    </a>
                                </li>
                                <li class="divider"></li>
                            @endif
                          @endif
                          
                      @endif
                      <li class="nav-item {{ $menu == 'profile' ? 'active' : '' }}"><a href="{{ action("Admin\AccountController@profile") }}" class="dropdown-item d-flex align-items-center">
                        <i class="navbar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 88.3 88.3" style="enable-background:new 0 0 88.3 88.3;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><g><path id="Layer_1-3_1_" class="st0" d="M44.2,1C20.4,0.9,1,20.1,0.9,43.9c0,12.4,5.2,24.1,14.5,32.4l0,0l0.6,0.6l0.4,0.3 c1.8,1.5,3.7,2.8,5.7,4.1l0.5,0.2l3,1.7h0.1c2.3,1,4.6,1.9,7,2.6h0.1l3.6,0.8h0.3l3.5,0.5h8l3.5-0.5H52l3.5-0.8h0.1 c2.4-0.7,4.8-1.6,7.1-2.6l0,0c1.1-0.5,2.1-1.1,3.1-1.7l0.5-0.2l2.6-1.8l0.7-0.5l2.4-1.8l0.3-0.3l0.7-0.6l0,0 c17.7-16,19.2-43.3,3.2-61C68,6.2,56.4,1,44.2,1z M44.2,44.2c-8,0-14.4-6.4-14.4-14.4c0-8,6.4-14.4,14.4-14.4s14.4,6.4,14.4,14.4 S52.2,44.2,44.2,44.2L44.2,44.2z M49.6,47.8c10.9,0,19.8,8.9,19.8,19.8v7.1l-0.3,0.2l-2.2,1.6l-0.4,0.4L64,78.4l-0.5,0.3 c-3.8,2.1-8,3.6-12.3,4.4h-0.3l-3.1,0.4h-7.2L37.2,83c-3.2-0.6-6.4-1.5-9.4-2.9h-0.3c-1.1-0.5-2.1-1.1-3.1-1.7l-2.5-1.5l-0.5-0.4 l-2.2-1.6c-0.1-0.1-0.1-0.2-0.2-0.2v-7.1c0-10.9,8.9-19.8,19.8-19.8h0H49.6z M73,71.3v-3.7c0-11.1-7.8-20.7-18.7-22.9 c4.9-3.4,7.8-8.9,7.9-14.9c0-9.9-8.1-18-18-18s-18,8.1-18,18c0,6,3,11.5,7.9,14.9c-10.9,2.2-18.7,11.8-18.7,22.9v3.7 C0.5,55.4,1.3,30.4,17.2,15.5S58.1,1.3,73,17.2C87.3,32.4,87.3,56.1,73,71.3z"/><path class="st0" d="M48.3,88.1l-8.2,0l-3.9-0.5l-3.8-0.8c-2.4-0.7-4.7-1.6-6.9-2.6h-0.1l-3.2-1.8l-0.6-0.3c-2-1.3-4-2.7-5.8-4.2 l-0.5-0.4L14.7,77C5.3,68.6-0.1,56.6-0.1,43.9C0,19.7,19.8,0,44,0c0.1,0,0.1,0,0.2,0c12.5,0,24.4,5.4,32.7,14.6 C93.2,32.7,91.8,60.7,73.7,77l-1.1,1l-3.1,2.3l-2.8,1.9l-0.4,0.2c-1,0.6-2.1,1.2-3.1,1.7c-2.4,1.1-4.8,1.9-7.3,2.7l-0.3,0 L52,87.6h-0.2L48.3,88.1z M40.3,86.1h7.9l3.8-0.5l3.6-0.8c2.3-0.7,4.6-1.5,6.8-2.5c1-0.5,2-1,3-1.6l0.2-0.1l0.4-0.2l3.2-2.2 l2.6-2l0.8-0.7C89.6,60,91,33.2,75.4,16C67.5,7.1,56.1,2,44.2,2h0c0,0-0.1,0-0.2,0C20.9,2,2,20.8,1.9,44 c0,12.1,5.1,23.6,14.1,31.6l0.6,0.6l0.3,0.3c1.8,1.5,3.6,2.8,5.6,4l0.5,0.2l3,1.7c2.2,1,4.4,1.8,6.8,2.5l0.2,0l3.8,0.8L40.3,86.1 z M47.9,84.5l-7.4,0L37.1,84c-3.3-0.6-6.5-1.5-9.5-2.9h-0.3L27.1,81c-1.1-0.5-2.2-1.1-3.2-1.8l-2.6-1.6l-0.5-0.4l-2.2-1.6H18 v-8.1c0-11.5,9.3-20.8,20.8-20.8h10.8c11.5,0,20.8,9.3,20.8,20.8v7.6l-0.7,0.5l-2.1,1.5L67,77.8l-3,1.8c-4,2.2-8.2,3.7-12.6,4.5 l-0.4,0L47.9,84.5z M40.7,82.5h7.1l3.4-0.4c4.2-0.8,8.2-2.2,11.9-4.3l2.9-1.7l0.4-0.4l2.1-1.5v-6.6c0-10.4-8.4-18.8-18.8-18.8 H38.8C28.4,48.8,20,57.2,20,67.6v6.6l2.5,1.8l2.4,1.5c0.9,0.6,1.9,1.1,2.8,1.6H28l0.2,0.1c2.9,1.3,6,2.3,9.2,2.8L40.7,82.5z M16.4,73.8L14.7,72c-7.4-7.9-11.3-18.2-11-29c0.3-10.8,4.9-20.8,12.8-28.2c16.3-15.3,42-14.5,57.2,1.8 c14.6,15.5,14.6,39.9,0,55.4L72,73.8v-6.2c0-10.6-7.5-19.8-17.9-21.9l-2.3-0.5l1.9-1.3c4.6-3.2,7.4-8.5,7.5-14.1 c0-9.4-7.6-17-17-17c-9.4,0-17,7.6-17,17c0,5.6,2.8,10.9,7.5,14.1l2,1.3l-2.3,0.5C23.9,47.8,16.4,57,16.4,67.6V73.8z M44.2,5.8 c-9.5,0-18.9,3.4-26.3,10.4C10.4,23.2,6,32.8,5.7,43c-0.3,9.4,2.8,18.4,8.7,25.6v-1c0-10.8,7.2-20.4,17.4-23.4 c-4.2-3.6-6.6-8.8-6.6-14.4c0-5.1,2-9.8,5.6-13.4s8.4-5.6,13.4-5.6c10.5,0,19,8.5,19,19c0,5.6-2.5,10.8-6.7,14.4 C66.8,47.3,74,56.8,74,67.6v1c12.1-14.8,11.5-36.6-1.7-50.7C64.7,9.9,54.5,5.8,44.2,5.8z M44.2,45.2c-8.5,0-15.4-6.9-15.4-15.4 s6.9-15.4,15.4-15.4c8.5,0,15.4,6.9,15.4,15.4S52.7,45.2,44.2,45.2z M44.2,16.4c-7.4,0-13.4,6-13.4,13.4c0,7.4,6,13.4,13.4,13.4 c7.4,0,13.4-6,13.4-13.4C57.6,22.4,51.6,16.4,44.2,16.4z"/></g></g></g></svg>
                            </i>{{ trans('messages.account') }}
                        </a></li>
                        <li class="nav-item {{ $menu == 'profile' ? 'api' : '' }}">
                            <a href="{{ action("Admin\AccountController@api") }}" class="dropdown-item d-flex align-items-center">
                            <i class="navbar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 100.9 112" style="enable-background:new 0 0 100.9 112;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M16.8,71c-0.6,0-1.3-0.2-1.9-0.6c-8-5.1-13.4-12.7-14.6-20.8c-0.9-5.8,0.3-11.1,3.5-15 c4.5-5.4,11.1-6.9,16.4-7.1c-0.9-4.5-1.1-10.7,2.5-16.4c3.2-5.3,9.1-9.1,16-10.6c7.5-1.5,15.4-0.1,21,3.9c5.2,3.7,7.7,9,8.9,13 c4.8-2.6,10.3-3.1,15-1.1c3.5,1.5,6.4,4.2,8.2,7.5c0.9,1.7,0.2,3.8-1.5,4.7c-1.7,0.9-3.8,0.2-4.7-1.5c-1-1.9-2.6-3.4-4.6-4.3 c-3.8-1.5-8.8-0.2-12.3,3.2c-1,1-2.4,1.3-3.7,0.8c-1.3-0.5-2.2-1.7-2.2-3.1c-0.1-0.9-0.7-9.1-7.1-13.7C51.5,7.3,45.7,6.3,40,7.5 c-5.1,1-9.2,3.7-11.5,7.4c-3.7,5.9-1.3,13.1-0.4,15.2c0.5,1.1,0.3,2.4-0.4,3.4c-0.7,1-1.9,1.5-3.1,1.4l-0.3,0 c-3.2-0.3-11-0.6-15.1,4.4c-1.9,2.3-2.6,5.7-2,9.4c1,6.1,5.2,11.9,11.5,16c1.6,1,2.1,3.2,1.1,4.8C19.1,70.4,18,71,16.8,71z"/><g><path class="st0" d="M68.2,58.6c5.8,3.3,7.9,10.8,4.5,16.6c-3.3,5.8-10.8,7.9-16.6,4.5c-5.6-3.2-7.7-10.2-4.9-16 c1.8-3.7,5.4-6.2,9.5-6.7c0.8-0.1,1.5,0.5,1.7,1.3c0.1,0.9-0.5,1.6-1.3,1.8c-3.1,0.3-5.8,2.2-7.1,5c-2.2,4.5-0.3,10,4.2,12.2 c4.5,2.2,10,0.3,12.2-4.2c2.2-4.5,0.3-9.9-4.2-12.1c-0.7-0.3-1.1-1-0.9-1.7c-0.1-0.2-0.2-0.5-0.2-0.7V33h-5.9v10.4l-1.3,0.3 c-6.4,1-12.2,4.5-16.2,9.7l-0.8,1.1L31.3,49l-3,5.1l9.8,5.7L37.7,61c-0.8,2.6-1.2,5.2-1.2,7.9c0,3.4,0.7,6.8,2,9.9l0.5,1.3 l-10.7,6.1l3,5.2l10.9-6.3l0.9,0.9c3.8,4.3,9.1,7.2,14.8,8.1l1.3,0.2v13.1h5.9V94.3l1.3-0.2c5.7-0.9,10.9-3.8,14.8-8.1l0.9-0.9 L93,91.4l3-5.2l-10.7-6.1l0.5-1.3c1.3-3.1,2-6.5,2-9.9c0-2.7-0.4-5.3-1.2-7.9l-0.4-1.2l9.8-5.7L93,49l-9.6,5.5l-0.8-1.1 c-2.6-3.3-5.9-6-9.7-7.7c-0.8-0.3-1.1-1.2-0.8-2c0,0,0-0.1,0.1-0.1c0.3-0.7,1.1-1.1,1.8-0.8c0.1,0,0.1,0.1,0.2,0.1 c3.9,1.7,7.3,4.3,10,7.6l7.9-4.6c1.1-0.6,2.5-0.2,3.2,0.9l3.7,6.5c0.6,1,0.3,2.4-0.7,3c0,0-0.1,0-0.1,0.1l-8.3,4.8 c0.7,2.5,1,5.1,1,7.7c0,3.3-0.6,6.7-1.7,9.8l9,5.2c1.1,0.6,1.5,2,0.8,3.1c0,0,0,0,0,0.1l-3.7,6.5c-0.6,1.1-2,1.5-3.1,0.8 c0,0,0,0-0.1,0L82.7,89c-4,4-9,6.7-14.5,7.9v11.3c0,1.3-1,2.3-2.3,2.3h0h-7.5c-1.3,0-2.3-1-2.3-2.3l0,0V96.9 C50.6,95.7,45.6,93,41.6,89l-9.4,5.4c-1.1,0.7-2.5,0.3-3.2-0.7c0,0,0,0,0-0.1l-3.8-6.5c-0.6-1.1-0.2-2.5,0.9-3.2l9-5.2 c-1.1-3.1-1.7-6.5-1.7-9.8c0-2.6,0.3-5.2,1-7.7l-8.3-4.8c-1.1-0.6-1.5-2-0.9-3.1l3.8-6.5c0.7-1.1,2.1-1.5,3.2-0.9l7.9,4.6 c4.1-4.9,9.7-8.3,16-9.6v-8.7c0-1.3,1-2.3,2.3-2.3h7.5c1.3,0,2.3,1,2.3,2.3c0,0,0,0,0,0S68.2,58.6,68.2,58.6z"/><path class="st0" d="M65.9,112h-7.5c-2.1,0-3.8-1.7-3.8-3.8V98.1c-4.9-1.3-9.5-3.7-13.2-7.2l-8.4,4.8c-1.7,1.1-4.1,0.5-5.2-1.3 l-0.1-0.1l-3.8-6.5c-1-1.9-0.4-4.2,1.4-5.2l8-4.6c-0.9-3-1.4-6-1.4-9.1c0-2.3,0.2-4.7,0.8-7l-7.3-4.2c-1.8-1-2.5-3.3-1.5-5.1 l3.8-6.5c1.1-1.8,3.3-2.5,5.2-1.5l6.9,4c3.9-4.3,9.1-7.4,14.8-8.9v-7.5c0-2.1,1.7-3.8,3.8-3.8h7.5c2.1,0,3.8,1.7,3.8,3.8v25.6 c6,3.9,7.9,11.9,4.3,18.2c-3.7,6.6-12.1,8.8-18.7,5.1c-6.3-3.6-8.7-11.5-5.5-17.9c2.1-4.2,6.1-7,10.7-7.5 c1.3-0.1,2.5,0.6,3.1,1.7V34.5h-2.9v10.1l-2.6,0.6c-6.1,1-11.5,4.2-15.2,9.1l-1.6,2.2L31.8,51l-1.5,2.5l9.6,5.6l-0.8,2.4 c-0.8,2.4-1.1,4.9-1.1,7.4c0,3.2,0.6,6.4,1.9,9.3l1,2.5l-10.5,6l1.5,2.6l10.6-6.1l1.7,1.7c3.7,4.1,8.6,6.8,14,7.7l2.6,0.4v12.9 h2.9V93l2.6-0.4c5.4-0.9,10.3-3.6,13.9-7.6l1.8-1.8l10.6,6.1l1.5-2.6l-10.5-6l1-2.5c1.3-3,1.9-6.1,1.9-9.4c0-2.5-0.4-5-1.1-7.4 l-0.8-2.3l9.6-5.6L92.5,51L83,56.5l-1.6-2.2c-2.4-3.1-5.5-5.6-9.1-7.2c-0.7-0.3-1.3-0.9-1.6-1.7c-0.3-0.7-0.3-1.6,0.1-2.3 c0,0,0-0.1,0.1-0.2c0.3-0.7,0.8-1.2,1.5-1.5c0.7-0.3,1.5-0.3,2.2,0c0.1,0,0.2,0.1,0.3,0.1c3.7,1.6,7,4,9.7,7l6.8-4 c1.9-1,4.2-0.4,5.2,1.4l3.7,6.5c0.5,0.8,0.7,1.8,0.4,2.7c-0.2,1-0.8,1.8-1.6,2.3c-0.1,0-0.1,0.1-0.2,0.1l-7.3,4.2 c0.5,2.3,0.8,4.6,0.8,7c0,3.1-0.5,6.2-1.4,9.1l7.9,4.6c0.9,0.5,1.5,1.3,1.8,2.3c0.3,1,0.1,2-0.4,2.9l-0.1,0.1l-3.7,6.4 c-0.5,0.9-1.3,1.5-2.3,1.8c-1,0.3-2,0.1-2.9-0.4l-8.5-4.9c-3.7,3.5-8.3,6-13.2,7.2v10.1C69.7,110.3,68,112,65.9,112z M58,108.9 c0.1,0.1,0.2,0.1,0.4,0.1h7.5c0.1,0,0.3,0,0.4-0.1H58z M66.6,95.6v13c0.1-0.1,0.1-0.2,0.1-0.4V95.7l1.2-0.3 c5.2-1.1,10-3.7,13.8-7.5l0.8-0.8l10.4,6c0.1,0.1,0.2,0.1,0.3,0.1L82.3,87l-0.1,0.1C78.3,91.6,72.7,94.6,66.6,95.6L66.6,95.6z M41.8,87.1l0.8,0.8c3.8,3.8,8.5,6.4,13.8,7.5l1.2,0.3v12.5c0,0.1,0,0.3,0.1,0.4l0-13C51.6,94.6,46,91.6,42,87l0,0l-10.8,6.3 c0.1,0,0.2-0.1,0.3-0.1L41.8,87.1z M97.8,86l-4.1,7.1c0.1-0.1,0.2-0.2,0.3-0.3l3.7-6.5C97.8,86.2,97.8,86.1,97.8,86z M26.4,85.9 c0,0.2,0,0.3,0.1,0.5l3.8,6.5c0.1,0.1,0.2,0.2,0.3,0.3L26.4,85.9z M26.5,54.8c0.1,0.1,0.2,0.2,0.3,0.3l9.3,5.4l-0.3,1.1 c-0.6,2.4-1,4.8-0.9,7.3c0,3.2,0.5,6.3,1.6,9.3l0.4,1.2l-10.1,5.8c-0.1,0.1-0.2,0.2-0.3,0.3l10.6-6l0-0.1 C35.7,76,35,72.5,35,68.9c0-2.8,0.4-5.6,1.3-8.4l0-0.1L26.5,54.8z M87.2,79.4l10.6,6c-0.1-0.1-0.2-0.2-0.3-0.3l-10.1-5.8l0.4-1.2 c1.1-3,1.6-6.1,1.6-9.3c0-2.5-0.3-4.9-0.9-7.3l-0.3-1.1l9.3-5.4c0.1-0.1,0.2-0.2,0.3-0.3L88,60.5l0,0c0.9,2.7,1.3,5.5,1.3,8.4 C89.3,72.5,88.6,76,87.2,79.4L87.2,79.4z M60.8,58.6C60.8,58.6,60.8,58.6,60.8,58.6c-3.6,0.4-6.7,2.6-8.3,5.9 c-2.5,5.1-0.6,11.2,4.3,14c5.1,2.9,11.7,1.1,14.6-4c2.9-5.1,1.1-11.7-4-14.6l-0.7-0.4l0,0.1l0.1,0c5.2,2.6,7.4,8.9,4.9,14.1 c-1.2,2.5-3.4,4.5-6.1,5.4c-2.7,0.9-5.6,0.8-8.1-0.5c-2.5-1.2-4.5-3.4-5.4-6.1s-0.8-5.6,0.5-8.1c1.5-3.2,4.6-5.5,8.2-5.8 C60.9,58.7,60.9,58.7,60.8,58.6C60.9,58.6,60.9,58.6,60.8,58.6z M63.8,59.5c-0.3,1.1-1.2,1.9-2.4,2.1l-0.1,0 c-2.5,0.2-4.8,1.8-5.9,4.1c-0.9,1.8-1,3.9-0.4,5.8s2,3.5,3.9,4.3c3.8,1.8,8.3,0.2,10.2-3.5c1.8-3.7,0.3-8.3-3.5-10.1 C64.4,61.9,63.7,60.8,63.8,59.5z M66.6,58.5c0,0,0,0.1,0,0.1l0.1,0.1V32.2c0-0.1,0-0.3-0.1-0.4V58.5z M30.6,47.3 c-0.1,0.1-0.2,0.2-0.3,0.3l-3.8,6.5c0,0.1-0.1,0.2-0.1,0.3L30.6,47.3z M93.7,47.3l3.7,6.4L94,47.5C93.9,47.4,93.8,47.3,93.7,47.3 z M73.6,44.3L73.6,44.3c4,1.9,7.5,4.7,10.2,8.2l0,0l9.5-5.4c-0.2,0-0.3,0-0.5,0.1l-9,5.2l-0.8-1C80.5,48.4,77.3,45.9,73.6,44.3 L73.6,44.3z M31,47.1l9.5,5.4l0,0c4.2-5.5,10.3-9.2,17.1-10.3l0.1,0V31.8c-0.1,0.1-0.1,0.2-0.1,0.4v9.9l-1.2,0.2 c-5.9,1.2-11.3,4.5-15.2,9.1l-0.8,1l-9-5.2C31.3,47.1,31.2,47.1,31,47.1z M58,31.5h8.3c-0.1-0.1-0.2-0.1-0.4-0.1h-7.5 C58.3,31.4,58.1,31.4,58,31.5z"/></g></g></g></svg>
                            </i>{{ trans('messages.admin_api') }}
                            </a>
                        </li>
                        <li><a href="{{ url("/logout") }}" class="dropdown-item d-flex align-items-center">
                            <i class="navbar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 83.9 85.1" style="enable-background:new 0 0 83.9 85.1;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M41.8,85.1c-7.9,0-15.7-2.3-22.5-6.6C-0.1,66-5.8,40,6.6,20.6c3.6-5.6,8.6-10.4,14.3-13.7 c1.7-1,3.8-0.4,4.8,1.3c1,1.7,0.4,3.8-1.3,4.8c-4.8,2.8-8.9,6.7-11.9,11.4C2.1,40.6,6.9,62.2,23.1,72.6 C39.3,82.9,60.9,78.2,71.3,62c5-7.9,6.7-17.2,4.7-26.3s-7.4-16.9-15.3-21.9c-1.6-1-2.1-3.2-1.1-4.8c1-1.6,3.2-2.1,4.8-1.1 c9.4,6,15.9,15.4,18.3,26.3s0.4,22.1-5.6,31.6c-6,9.4-15.4,15.9-26.3,18.3C47.9,84.7,44.8,85.1,41.8,85.1z"/><path class="st0" d="M41.9,41.2c-1.9,0-3.5-1.6-3.5-3.5V3.5C38.4,1.6,40,0,41.9,0s3.5,1.6,3.5,3.5v34.2 C45.4,39.6,43.8,41.2,41.9,41.2z"/></g></g></svg>
                            </i>{{ trans('messages.logout') }}</a></li>
                    </ul>
                  </li>
                  <!--/ User -->
                </ul>
              </div>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper {{ $menu }}">
            <!-- Content -->

            

            <div class="container-xxl flex-grow-1 mb-5">
                <div class="pt-3">
                  @yield('page_header')
                </div>
                @yield('content')
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  {!! trans('athena.copy_right') !!}
                </div>
                <div>
                  <a href="https://athenaevs.com/license/" class="footer-link me-4" target="_blank">{{ trans('athena.license') }}</a>
                  <a href="https://athenaevs.com/" target="_blank" class="footer-link me-4">{{ trans('athena.contact_us') }}</a>

                  <a
                    href="https://athenaevs.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                    target="_blank"
                    class="footer-link me-4"
                    >{{ trans('athena.documentation') }}</a
                  >

                  <a
                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                    target="_blank"
                    class="footer-link me-4"
                    >{{ trans('athena.support') }}</a
                  >
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

     

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    {{-- <script src="{{ AppUrl::asset('em/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ AppUrl::asset('em/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ AppUrl::asset('em/assets/vendor/js/bootstrap.js') }}"></script> --}}
    <script src="{{ AppUrl::asset('em/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ AppUrl::asset('em/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ AppUrl::asset('em/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ AppUrl::asset('em/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ AppUrl::asset('em/assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>