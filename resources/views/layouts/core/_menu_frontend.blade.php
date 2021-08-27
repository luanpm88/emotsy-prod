<nav class="navbar navbar-expand-xl navbar-dark fixed-top navbar-main py-0">
    <div class="container-fluid ms-0">
        <a class="navbar-brand d-flex align-items-center me-2" href="{{ action('HomeController@index') }}">
            @if (\Acelle\Model\Setting::get('site_logo_small'))
                <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
            @else
                <img class="logo default-logo" src="{{ URL::asset('images/logo-' . (Auth::user()->customer->getColorScheme() == "white" ? "dark" : "light") . '.svg') }}" alt="">
            @endif
        </a>
        <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="leftbar-hide-menu middle-bar-element">
            <svg class="SideBurgerIcon-image" viewBox="0 0 50 32"><path d="M49,4H19c-0.6,0-1-0.4-1-1s0.4-1,1-1h30c0.6,0,1,0.4,1,1S49.6,4,49,4z"></path><path d="M49,16H19c-0.6,0-1-0.4-1-1s0.4-1,1-1h30c0.6,0,1,0.4,1,1S49.6,16,49,16z"></path><path d="M49,28H19c-0.6,0-1-0.4-1-1s0.4-1,1-1h30c0.6,0,1,0.4,1,1S49.6,28,49,28z"></path><path d="M8.1,22.8c-0.3,0-0.5-0.1-0.7-0.3L0.7,15l6.7-7.8c0.4-0.4,1-0.5,1.4-0.1c0.4,0.4,0.5,1,0.1,1.4L3.3,15l5.5,6.2   c0.4,0.4,0.3,1-0.1,1.4C8.6,22.7,8.4,22.8,8.1,22.8z"></path></svg>
        </span>

        @php			
            $iconDir = "";
            if (Auth::user()->customer->getColorScheme() == 'white') {
                $iconDir = "dark/";
            }
        @endphp
        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-md-0 main-menu">
                <li class="nav-item" rel0="HomeController">
                    <a href="{{ action('HomeController@index') }}" title="{{ trans('messages.dashboard') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img src="{{ url('images/icons/'.$iconDir.'SVG/home.svg') }}" />
                        </i>
                        <span>{{ trans('messages.dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="CampaignController">
                    <a title="{{ trans('messages.campaigns') }}" href="{{ action('CampaignController@index') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img src="{{ url('images/icons/'.$iconDir.'SVG/campaign.svg') }}" />
                        </i>
                        <span>{{ trans('messages.campaigns') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="Automation2Controller">
                    <a href="{{ action('Automation2Controller@index') }}" title="{{ trans('messages.automations') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/automation.svg') }}" />
                        </i>
                        <span>{{ trans('messages.automations') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="MailListController">
                    <a href="{{ action('MailListController@index') }}" title="{{ trans('messages.lists') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img style="width: 21px" src="{{ url('images/icons/'.$iconDir.'SVG/list.svg') }}" />
                        </i>
                        <span>{{ trans('messages.lists') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="TemplateController">
                    <a href="{{ action('TemplateController@index') }}" title="{{ trans('messages.templates') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img style="width: 18px" src="{{ url('images/icons/'.$iconDir.'SVG/template.svg') }}" />
                        </i>
                        <span>{{ trans('messages.templates') }}</span>
                    </a>
                </li>
                @if (config('custom.woo'))
                <li class="nav-item dropdown">
                    <a href="" title="{{ trans('messages.content') }}"
                        class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle"
                        id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="navbar-icon">
                            <img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/content.svg') }}" />
                        </i>
                        <span>{{ trans('messages.content') }}</span>
                    </a>
                    <ul class="dropdown-menu shadow" aria-labelledby="content-menu">
                        <li class="nav-item" rel0="ProductController">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('ProductController@index') }}">
                                <i class="navbar-icon" style="">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 88.3 71.5" style="enable-background:new 0 0 88.3 71.5;" xml:space="preserve"> <style type="text/css"> .st0{fill:#93A8C1;}.st1{fill:#414042;}</style> <g id="Layer_2_1_"> <g id="Layer_1-2"> <rect x="18.4" y="48" class="st0" width="52.5" height="10.1"/> <rect x="18.4" y="30.8" class="st0" width="52.5" height="10.1"/> <path class="st1" d="M71.3,71.5C71.3,71.5,71.3,71.5,71.3,71.5L17.7,71c-0.6,0-1.2-0.3-1.7-0.7s-0.7-1.1-0.7-1.7l0.9-38l-4.4,2.7 c-0.6,0.4-1.3,0.5-1.9,0.3c-0.6-0.2-1.2-0.6-1.5-1.2c-2.7-5.5-5.4-11-8.2-16.5c-0.4-0.9-0.3-1.9,0.3-2.6c4.4-5.1,10-9.1,16.3-11.4 c4.7-1.6,7.7-1.5,13.6-1.3c3,0.1,6.8,0.2,12.2,0.2c6.2-0.1,10.4-0.3,13.5-0.5c5.3-0.3,8-0.5,12.5,0.8c7.3,2.2,13.8,6.3,19,11.9 c0.6,0.7,0.8,1.7,0.4,2.6L80.6,32c-0.3,0.6-0.8,1.1-1.4,1.3c-0.6,0.2-1.3,0.1-1.9-0.2l-4.7-2.7l1.1,38.7c0,0.6-0.2,1.3-0.7,1.7 C72.6,71.2,72,71.5,71.3,71.5z M20.1,66.2l48.7,0.5l-1.2-40.5c0-0.9,0.4-1.7,1.2-2.1c0.7-0.4,1.7-0.5,2.4,0l6.1,3.5L83,15 c-4.4-4.4-9.8-7.6-15.7-9.4C63.6,4.6,61.5,4.7,56.4,5C53.3,5.2,49,5.4,42.7,5.5c-5.5,0-9.3-0.1-12.4-0.2c-5.8-0.2-8-0.3-11.8,1.1 c-5,1.8-9.6,4.9-13.3,8.8c2.1,4.2,4.2,8.5,6.3,12.7l5.9-3.7c0.7-0.5,1.7-0.5,2.4,0c0.8,0.4,1.2,1.3,1.2,2.1L20.1,66.2z"/> <path class="st1" d="M44.4,18c-3.5,0-6.9-1.6-10-4.8c-2.6-2.8-4.2-6.3-4.5-10c-0.1-1.3,0.8-2.5,2.1-2.6c1.3-0.1,2.5,0.8,2.6,2.1 c0.3,2.6,1.4,5.2,3.2,7.1c0.9,0.9,3.5,3.6,7,3.3c3.9-0.3,6.4-3.9,7.5-6.1c0.8-1.5,1.3-3.2,1.6-4.9c0.2-1.3,1.4-2.2,2.7-2 c1.3,0.2,2.2,1.4,2,2.7c-0.3,2.2-1,4.3-2.1,6.3c-2.7,5.3-6.8,8.4-11.4,8.7C44.9,18,44.7,18,44.4,18z"/> </g> </g> </svg>
                                </i>
                                <span>{{ trans('messages.products') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SourceController">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('SourceController@index') }}">
                                <i class="navbar-icon" style="">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 94.4 85.6" style="enable-background:new 0 0 94.4 85.6;" xml:space="preserve"><style type="text/css">.st0{fill:#93C2A0;}.st1{fill:#414042;}.st2{fill:#877083;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M37,58.5h18.1c2.3,0,4.2,1.9,4.2,4.2V78c0,2.3-1.9,4.2-4.2,4.2H37c-2.3,0-4.2-1.9-4.2-4.2V62.7 C32.8,60.4,34.7,58.5,37,58.5z"/><path class="st1" d="M94.4,32.8c0-1.9-0.3-3.8-0.9-5.6l0,0c0-0.3,0-0.3-0.3-0.6l-8-19.8c-1.5-4.2-5.6-6.9-10-6.8H19.2 c-4.7,0-8.3,2.4-9.7,6.5L0.9,26.8v0.6C0.3,29.3,0,31.3,0,33.3c-0.1,5.2,2.1,10.1,5.9,13.6v29.8c0,4.9,4,8.8,8.9,8.9h64 c4.9,0,8.8-4,8.9-8.9V48.1C91.7,43.7,94.4,38.4,94.4,32.8L94.4,32.8z M78.8,77.9h-64c-0.9,0-1.7-0.7-1.8-1.6c0-0.1,0-0.1,0-0.2 V50.5c2,0.6,4.1,0.9,6.2,0.9c5.5,0.1,10.8-2.4,14.2-6.8c3.5,4.2,8.7,6.5,14.2,6.5c5.5,0.1,10.8-2.4,14.2-6.8 c3.6,4.2,8.9,6.7,14.5,6.8c1.6,0,3.2-0.2,4.7-0.6v25.4C80.7,76.9,79.9,77.7,78.8,77.9L78.8,77.9z M81.4,43.1 c-1.7,0.9-3.7,1.3-5.6,1.2c-4.1,0-7.9-2.1-10-5.6c-0.3-0.3-0.3-0.9-0.9-1.5c-0.8-0.9-2-1.5-3.2-1.5c-1.3-0.1-2.5,0.5-3.2,1.5 c-0.4,0.4-0.7,0.9-0.9,1.5c-3.4,5.5-10.7,7.3-16.2,3.9c-1.6-1-2.9-2.3-3.9-3.9c-0.3-0.3-0.3-0.9-0.9-1.2c-1.5-1.8-5.3-1.8-6.5,0 c-0.4,0.4-0.8,0.9-0.9,1.5c-2.1,3.5-5.9,5.7-10,5.6c-1.9,0.1-3.9-0.3-5.6-1.2l0,0c-3.9-2.1-6.2-6.2-6.2-10.6 c0-1.3,0.2-2.6,0.6-3.8v-0.3l8.5-20.1c0.3-0.6,0.6-2.1,3.2-2.1h56.1c1.5-0.2,3,0.7,3.5,2.1l8,19.8v0.3c0.3,1.3,0.5,2.5,0.6,3.8 C87.8,36.9,85.3,40.9,81.4,43.1L81.4,43.1z"/><path class="st2" d="M67.1,27.3H26.7c-2.2,0-4-1.8-4-4s1.8-4,4-4h40.4c2.2,0,4,1.8,4,4S69.3,27.3,67.1,27.3z"/></g></g></svg>
                                </i>
                                <span>{{ trans('messages.stores_connections') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (
                    Auth::user()->customer->can("read", new Acelle\Model\SendingServer()) ||					
                    Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()) ||
                    Auth::user()->customer->can("read", new Acelle\Model\Blacklist()) ||
                    true
                )
                    <li class="nav-item dropdown">
                        <a title="{{ trans('messages.sending') }}" href="{{ action('TemplateController@index') }}" class="leftbar-tooltip nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="sending-menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="navbar-icon">
                                <img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/sending.svg') }}" />
                            </i>
                            <span>{{ trans('messages.sending') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-bottom" aria-labelledby="sending-menu">
                            @if (Auth::user()->customer->can("read", new Acelle\Model\SendingServer()))
                                <li class="nav-item" rel0="SendingServerController">
                                    <a href="{{ action('SendingServerController@index') }}"
                                        class="dropdown-item d-flex align-items-center">
                                        <i class="navbar-icon mr-2" style="width:19px">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 91.6 90.9" style="enable-background:new 0 0 91.6 90.9;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M83.9,90.9H7.7c-4.2,0-7.7-3.1-7.7-6.8V6.8C0,3.1,3.5,0,7.7,0h76.2c4.2,0,7.7,3.1,7.7,6.8v77.3 C91.6,87.8,88.1,90.9,83.9,90.9z M7,83.7c0.1,0.1,0.4,0.2,0.7,0.2h76.2c0.3,0,0.6-0.1,0.7-0.2V7.2C84.5,7.1,84.2,7,83.9,7H7.7 C7.4,7,7.1,7.1,7,7.2V83.7z"/><path class="st0" d="M85.6,61.6H3.5C1.6,61.6,0,60,0,58.1s1.6-3.5,3.5-3.5h82.1c1.9,0,3.5,1.6,3.5,3.5S87.5,61.6,85.6,61.6z"/><path class="st0" d="M88.1,33.6H3.5C1.6,33.6,0,32,0,30.1s1.6-3.5,3.5-3.5h84.6c1.9,0,3.5,1.6,3.5,3.5S90,33.6,88.1,33.6z"/><path class="st0" d="M72,22.2c-3.6,0-6.5-2.8-6.5-6.3c0-3.5,2.9-6.3,6.5-6.3s6.5,2.8,6.5,6.3C78.5,19.4,75.6,22.2,72,22.2z M72,15.2c-0.2,0-0.5,0.3-0.5,0.7c0,0.4,0.3,0.7,0.5,0.7s0.5-0.3,0.5-0.7C72.5,15.5,72.2,15.2,72,15.2z"/><path class="st0" d="M72,49.9c-3.6,0-6.5-2.8-6.5-6.3s2.9-6.3,6.5-6.3s6.5,2.8,6.5,6.3S75.6,49.9,72,49.9z M72,42.9 c-0.2,0-0.5,0.3-0.5,0.7s0.3,0.7,0.5,0.7s0.5-0.3,0.5-0.7S72.2,42.9,72,42.9z"/><path class="st0" d="M72,80.5c-3.6,0-6.5-2.8-6.5-6.3s2.9-6.3,6.5-6.3s6.5,2.8,6.5,6.3S75.6,80.5,72,80.5z M72,73.5 c-0.2,0-0.5,0.3-0.5,0.7s0.3,0.7,0.5,0.7s0.5-0.3,0.5-0.7S72.2,73.5,72,73.5z"/><path class="st0" d="M57,19.4H18.9c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H57c1.9,0,3.5,1.6,3.5,3.5S58.9,19.4,57,19.4z"/><path class="st0" d="M57,47.9H18.9c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H57c1.9,0,3.5,1.6,3.5,3.5S58.9,47.9,57,47.9z"/><path class="st0" d="M57,77.7H18.9c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H57c1.9,0,3.5,1.6,3.5,3.5S58.9,77.7,57,77.7z"/></g></g></svg>
                                    </i> {{ trans('messages.sending_servers') }}
                                    </a>
                                </li>
                            @endif
                            <li rel1="SendingDomainController">
                                <a href="{{ action('SendingDomainController@index') }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-2" style="width:20	px">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 77.4 77.5" style="enable-background:new 0 0 77.4 77.5;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M38.7,77.4c-10.2,0-20.3-4-27.9-11.9C-4.1,50.1-3.5,25.6,11.9,10.8l0.4-0.4h0.1C27.3-3.3,50.1-3.3,65,10.4 h0.1l0.5,0.5l1,1.1c14.7,15.4,14.2,39.9-1.2,54.7C58,73.9,48.3,77.4,38.7,77.4z M5.8,41.5c1.5,17,15.6,30.2,32.9,30.3 c17.3,0,31.5-13.2,32.9-30.3H5.8z M61.4,35.9h10.3C71,28.8,68.1,22,63.4,16.7c-1.7,1.4-3.5,2.7-5.4,3.8 C60,25.4,61.1,30.6,61.4,35.9z M41.5,35.9h14.2c-0.3-4.5-1.3-8.8-2.9-13c-3.6,1.4-7.5,2.3-11.4,2.6V35.9z M21.7,35.9h14.2V25.5 c-3.9-0.3-7.7-1.2-11.4-2.6C22.9,27.1,22,31.4,21.7,35.9z M5.8,35.9h10.3c0.3-5.3,1.4-10.5,3.4-15.4c-1.9-1.1-3.7-2.3-5.4-3.8 C9.3,22,6.4,28.8,5.8,35.9z M41.5,7.2v12.6c3.1-0.3,6.1-0.9,9-2.1C48.2,13.7,45.1,10.1,41.5,7.2z M27,17.8 c2.9,1.1,5.8,1.8,8.9,2.1V7.2C32.3,10.1,29.3,13.7,27,17.8z M18.2,12.8c1.2,0.9,2.4,1.8,3.7,2.5c1.5-2.7,3.2-5.3,5.3-7.6 C23.9,8.9,20.9,10.6,18.2,12.8z M50.2,7.7c2,2.3,3.8,4.9,5.3,7.6c1.3-0.8,2.5-1.6,3.7-2.5C56.5,10.6,53.5,8.9,50.2,7.7z"/><rect x="22.5" y="51" class="st0" width="6.3" height="7"/><rect x="34.8" y="51" class="st0" width="7.2" height="7"/><rect x="47.5" y="51" class="st0" width="7.4" height="7"/></g></g></svg>
                                    </i> {{ trans('messages.sending_domains') }}
                                </a>
                            </li>
                            <li class="nav-item" rel0="SenderController">
                                <a href="{{ action('SenderController@index') }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-2" style="width:20	px">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 114.7 98.6" style="enable-background:new 0 0 114.7 98.6;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M59.9,96.5H12.3C5.4,96.5,0,90,0,81.8V14.7C0,6.5,5.4,0,12.3,0h71.2c6.7,0,12.2,6.6,12.2,14.7v22.2 c0,1.9-1.6,3.5-3.5,3.5s-3.5-1.6-3.5-3.5V14.7c0-4.2-2.4-7.7-5.2-7.7H12.3C9.4,7,7,10.5,7,14.7v67.1c0,4.2,2.4,7.7,5.3,7.7h47.6 c1.9,0,3.5,1.6,3.5,3.5S61.8,96.5,59.9,96.5z"/><path class="st0" d="M30.8,43.7c-7.3,0-13.2-5.9-13.2-13.2s5.9-13.2,13.2-13.2c7.3,0,13.2,5.9,13.2,13.2S38.1,43.7,30.8,43.7z M30.8,24.3c-3.4,0-6.2,2.8-6.2,6.2s2.8,6.2,6.2,6.2c3.4,0,6.2-2.8,6.2-6.2S34.2,24.3,30.8,24.3z"/><path class="st0" d="M74.8,32.3H53.7c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5h21.1c1.9,0,3.5,1.6,3.5,3.5S76.7,32.3,74.8,32.3z"/><path class="st0" d="M66.2,43.6H53.7c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5h12.5c1.9,0,3.5,1.6,3.5,3.5S68.1,43.6,66.2,43.6z"/><path class="st0" d="M86.4,98.6c-7.6,0-14.7-2.9-20-8.3c-11-11-11-29,0-40l0,0c5.3-5.3,12.5-8.3,20-8.3c7.6,0,14.7,2.9,20,8.3 c5.3,5.3,8.3,12.5,8.3,20s-2.9,14.7-8.3,20C101.1,95.7,94,98.6,86.4,98.6z M71.3,55.3c-8.3,8.3-8.3,21.8,0,30.1 c4,4,9.4,6.2,15.1,6.2c5.7,0,11-2.2,15.1-6.2s6.2-9.4,6.2-15.1s-2.2-11-6.2-15.1c-4-4-9.4-6.2-15.1-6.2S75.4,51.2,71.3,55.3 L71.3,55.3z"/><path class="st0" d="M82.1,80c-1,0-2-0.4-2.7-1.3l-8.8-10.5c-1.2-1.5-1-3.7,0.4-4.9c1.5-1.2,3.7-1,4.9,0.4l6.7,8l14.8-10.6 c1.6-1.1,3.8-0.8,4.9,0.8s0.8,3.8-0.8,4.9L84.1,79.3C83.5,79.8,82.8,80,82.1,80z"/><path class="st0" d="M12,77.5c-1.9,0-3.5-1.6-3.5-3.5c0-11.6,9.6-29.5,22.4-29.5c13,0,22.4,16.6,22.4,27.8c0,1.9-1.6,3.5-3.5,3.5 s-3.5-1.6-3.5-3.5c0-10.5-8.8-20.8-15.4-20.8c-7.9,0-15.4,14-15.4,22.5C15.5,75.9,13.9,77.5,12,77.5z"/><path class="st0" d="M30.9,77.5c-1.9,0-3.5-1.6-3.5-3.5V56.6c0-1.9,1.6-3.5,3.5-3.5s3.5,1.6,3.5,3.5V74 C34.4,75.9,32.8,77.5,30.9,77.5z"/><polygon class="st0" points="25,58.3 30.9,48 36.9,58.3 "/></g></g></svg>
                                    </i> {{ trans('messages.verified_senders') }}
                                </a>
                            </li>
                            <li class="nav-item" rel0="TrackingDomainController">
                                <a href="{{ action('TrackingDomainController@index') }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-2" style="width:20px">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 77.4 77.5" style="enable-background:new 0 0 77.4 77.5;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M38.7,77.4c-10.2,0-20.3-4-27.9-11.9C-4.1,50.1-3.5,25.6,11.9,10.8l0.4-0.4h0.1C27.3-3.3,50.1-3.3,65,10.4 h0.1l0.5,0.5l1,1.1c14.7,15.4,14.2,39.9-1.2,54.7C58,73.9,48.3,77.4,38.7,77.4z M5.8,41.5c1.5,17,15.6,30.2,32.9,30.3 c17.3,0,31.5-13.2,32.9-30.3H5.8z M61.4,35.9h10.3C71,28.8,68.1,22,63.4,16.7c-1.7,1.4-3.5,2.7-5.4,3.8 C60,25.4,61.1,30.6,61.4,35.9z M41.5,35.9h14.2c-0.3-4.5-1.3-8.8-2.9-13c-3.6,1.4-7.5,2.3-11.4,2.6V35.9z M21.7,35.9h14.2V25.5 c-3.9-0.3-7.7-1.2-11.4-2.6C22.9,27.1,22,31.4,21.7,35.9z M5.8,35.9h10.3c0.3-5.3,1.4-10.5,3.4-15.4c-1.9-1.1-3.7-2.3-5.4-3.8 C9.3,22,6.4,28.8,5.8,35.9z M41.5,7.2v12.6c3.1-0.3,6.1-0.9,9-2.1C48.2,13.7,45.1,10.1,41.5,7.2z M27,17.8 c2.9,1.1,5.8,1.8,8.9,2.1V7.2C32.3,10.1,29.3,13.7,27,17.8z M18.2,12.8c1.2,0.9,2.4,1.8,3.7,2.5c1.5-2.7,3.2-5.3,5.3-7.6 C23.9,8.9,20.9,10.6,18.2,12.8z M50.2,7.7c2,2.3,3.8,4.9,5.3,7.6c1.3-0.8,2.5-1.6,3.7-2.5C56.5,10.6,53.5,8.9,50.2,7.7z"/><rect x="22.5" y="51" class="st0" width="6.3" height="7"/><rect x="34.8" y="51" class="st0" width="7.2" height="7"/><rect x="47.5" y="51" class="st0" width="7.4" height="7"/></g></g></svg>
                                    </i> {{ trans('messages.tracking_domains') }}
                                </a>
                            </li>
                            @if (Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()))
                                <li class="nav-item" rel0="EmailVerificationServerController">
                                    <a href="{{ action('EmailVerificationServerController@index') }}" class="dropdown-item d-flex align-items-center">
                                    <i class="navbar-icon mr-2" style="width:20px">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 91.6 90.9" style="enable-background:new 0 0 91.6 90.9;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><path class="st0" d="M83.9,90.9H7.7c-4.2,0-7.7-3.1-7.7-6.8V6.8C0,3.1,3.5,0,7.7,0h76.2c4.2,0,7.7,3.1,7.7,6.8v77.3 C91.6,87.8,88.1,90.9,83.9,90.9z M7,83.7c0.1,0.1,0.4,0.2,0.7,0.2h76.2c0.3,0,0.6-0.1,0.7-0.2V7.2C84.5,7.1,84.2,7,83.9,7H7.7 C7.4,7,7.1,7.1,7,7.2V83.7z"/><path class="st0" d="M85.6,61.6H3.5C1.6,61.6,0,60,0,58.1s1.6-3.5,3.5-3.5h82.1c1.9,0,3.5,1.6,3.5,3.5S87.5,61.6,85.6,61.6z"/><path class="st0" d="M88.1,33.6H3.5C1.6,33.6,0,32,0,30.1s1.6-3.5,3.5-3.5h84.6c1.9,0,3.5,1.6,3.5,3.5S90,33.6,88.1,33.6z"/><path class="st0" d="M72,22.2c-3.6,0-6.5-2.8-6.5-6.3c0-3.5,2.9-6.3,6.5-6.3s6.5,2.8,6.5,6.3C78.5,19.4,75.6,22.2,72,22.2z M72,15.2c-0.2,0-0.5,0.3-0.5,0.7c0,0.4,0.3,0.7,0.5,0.7s0.5-0.3,0.5-0.7C72.5,15.5,72.2,15.2,72,15.2z"/><path class="st0" d="M72,49.9c-3.6,0-6.5-2.8-6.5-6.3s2.9-6.3,6.5-6.3s6.5,2.8,6.5,6.3S75.6,49.9,72,49.9z M72,42.9 c-0.2,0-0.5,0.3-0.5,0.7s0.3,0.7,0.5,0.7s0.5-0.3,0.5-0.7S72.2,42.9,72,42.9z"/><path class="st0" d="M72,80.5c-3.6,0-6.5-2.8-6.5-6.3s2.9-6.3,6.5-6.3s6.5,2.8,6.5,6.3S75.6,80.5,72,80.5z M72,73.5 c-0.2,0-0.5,0.3-0.5,0.7s0.3,0.7,0.5,0.7s0.5-0.3,0.5-0.7S72.2,73.5,72,73.5z"/><path class="st0" d="M57,19.4H18.9c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H57c1.9,0,3.5,1.6,3.5,3.5S58.9,19.4,57,19.4z"/><path class="st0" d="M57,47.9H18.9c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H57c1.9,0,3.5,1.6,3.5,3.5S58.9,47.9,57,47.9z"/><path class="st0" d="M57,77.7H18.9c-1.9,0-3.5-1.6-3.5-3.5s1.6-3.5,3.5-3.5H57c1.9,0,3.5,1.6,3.5,3.5S58.9,77.7,57,77.7z"/></g></g></svg>
                                    </i> {{ trans('messages.email_verification_servers') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->customer->can("read", new Acelle\Model\Blacklist()))
                                <li class="nav-item" rel0="BlacklistController">
                                    <a href="{{ action('BlacklistController@index') }}" class="dropdown-item d-flex align-items-center">
                                    <i class="navbar-icon mr-2" style="width:20px">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 65.6 65.6" style="enable-background:new 0 0 65.6 65.6;" xml:space="preserve"><style type="text/css">.st0{fill:#333333;}</style><g id="Layer_2_1_"><g id="Layer_1-2"><g><path class="st0" d="M42.7,56.8c-1.1,0-2,0.9-2,2v1.8H5v-10c-0.1-9.7,7.7-17.7,17.4-17.9h0.9c3.7,0.1,7.2,1.3,10.2,3.5 c0.9,0.7,2.1,0.5,2.8-0.4c0.6-0.9,0.4-2.1-0.5-2.8c-1.6-1.2-3.3-2.2-5.2-2.9c7.5-4.3,10.2-13.9,5.9-21.4S22.6-1.5,15.1,2.8 S4.9,16.7,9.2,24.2c1.4,2.5,3.4,4.5,5.9,5.9C6.6,33.3,1,41.5,1,50.6v10.5c0,1.9,1.6,3.5,3.5,3.5h36.7c1.9,0,3.5-1.6,3.5-3.5v-2.3 C44.7,57.7,43.8,56.8,42.7,56.8z M11.2,16.6C11.3,10.1,16.6,5,23,5c3,0,5.9,1.2,8.1,3.4c4.5,4.5,4.5,11.9,0,16.4 c-2.2,2.2-5.2,3.4-8.3,3.4C16.4,28.2,11.2,23,11.2,16.6z"/><path class="st0" d="M41.2,65.6H4.5c-2.5,0-4.5-2-4.5-4.5V50.6c0-8.8,5.1-16.9,13-20.7c-1.9-1.4-3.5-3.2-4.7-5.2 C6.1,20.8,5.6,16.3,6.8,12c1.2-4.3,4-7.9,7.9-10.1c3.9-2.2,8.4-2.8,12.7-1.6c4.3,1.2,7.9,4,10.1,7.9c4.2,7.4,2.1,16.8-4.7,21.7 c1.3,0.6,2.5,1.4,3.7,2.3c1.3,1,1.6,2.8,0.7,4.1c-0.5,0.7-1.2,1.1-2,1.2c-0.8,0.1-1.6-0.1-2.2-0.6c-2.8-2.1-6.1-3.2-9.6-3.3 l-0.9,0C13.3,33.9,5.9,41.4,6,50.6v9h33.7v-0.8c0-1.7,1.3-3,3-3s3,1.3,3,3v2.3C45.7,63.6,43.7,65.6,41.2,65.6z M22.8,1.7 c-2.5,0-5,0.6-7.2,1.9c-7,4-9.5,13-5.5,20c1.3,2.3,3.2,4.2,5.5,5.5l1.8,1l-2,0.8C7.4,34.1,2,42,2,50.6v10.5 c0,1.4,1.1,2.5,2.5,2.5h36.7c1.4,0,2.5-1.1,2.5-2.5v-2.3c0-0.6-0.4-1-1-1s-1,0.4-1,1v2.8H4v-11c-0.1-10.2,8.1-18.7,18.4-18.9h0.9 c3.9,0.1,7.7,1.4,10.8,3.7c0.2,0.2,0.5,0.2,0.7,0.2c0.3,0,0.5-0.2,0.7-0.4c0.3-0.4,0.1-1.1-0.3-1.4c-1.5-1.2-3.2-2.1-4.9-2.8 l-2-0.8l1.9-1.1c7-4,9.5-13,5.5-20c-1.9-3.4-5.1-5.9-8.9-6.9C25.5,1.9,24.1,1.7,22.8,1.7z M22.9,29.2c0,0-0.1,0-0.1,0 c-6.9,0-12.6-5.7-12.6-12.6v0c0-3.4,1.4-6.6,3.8-8.9s5.7-3.6,9-3.6c3.3,0,6.4,1.3,8.7,3.6c2.4,2.4,3.7,5.6,3.7,8.9 s-1.3,6.5-3.7,8.9C29.5,27.9,26.2,29.2,22.9,29.2z M12.2,16.6c0,5.8,4.8,10.6,10.6,10.6c2.8,0,5.6-1.1,7.6-3.1 c2-2,3.1-4.7,3.1-7.5c0-2.8-1.1-5.5-3.1-7.5c-2-1.9-4.6-3-7.4-3.1c-2.9,0-5.6,1-7.6,3C13.4,11.1,12.2,13.8,12.2,16.6z"/></g><g><path class="st0" d="M55.5,40.7l8.5-8.5c0.7-0.9,0.5-2.1-0.4-2.8c-0.7-0.5-1.7-0.5-2.4,0l-8.6,8.5l-8.5-8.5 c-0.9-0.7-2.1-0.5-2.8,0.4c-0.5,0.7-0.5,1.7,0,2.4l8.5,8.5l-8.5,8.6c-0.8,0.7-0.8,1.9-0.1,2.7c0,0,0.1,0.1,0.1,0.1 c0.7,0.8,1.9,0.8,2.7,0.1c0,0,0.1-0.1,0.1-0.1l8.5-8.6l8.6,8.6c0.7,0.8,1.9,0.8,2.7,0.1c0,0,0.1-0.1,0.1-0.1 c0.8-0.7,0.8-1.9,0.1-2.7c0,0-0.1-0.1-0.1-0.1L55.5,40.7z"/><path class="st0" d="M62.6,53.7c-0.8,0-1.6-0.3-2.1-0.9l-7.9-7.9l-7.8,7.9c0,0-0.1,0.1-0.1,0.1c-1.2,1.1-3,1-4.1-0.2c0,0,0,0,0,0 c0,0-0.1-0.1-0.1-0.1c-1.1-1.2-1-3,0.2-4.1l7.8-7.9l-7.9-7.9c-0.8-1.1-0.8-2.5,0-3.6c1-1.3,2.9-1.6,4.2-0.6l0.1,0.1l7.8,7.8 l8-7.9c1.1-0.8,2.5-0.8,3.6,0c0.6,0.5,1.1,1.2,1.2,2c0.1,0.8-0.1,1.6-0.6,2.2l-0.1,0.1l-7.8,7.8l7.8,7.9c0,0,0.1,0.1,0.1,0.1 c0.5,0.6,0.8,1.3,0.8,2.1c0,0.8-0.4,1.5-0.9,2c0,0-0.1,0.1-0.1,0.1C64,53.5,63.3,53.7,62.6,53.7z M42.1,31.6l9.1,9.1L42,50 c-0.4,0.4-0.4,0.9-0.1,1.3l0.1,0.1c0.3,0.4,0.9,0.4,1.3,0.1l0.1-0.1l9.2-9.3l9.3,9.3c0.4,0.4,0.9,0.4,1.3,0.1l0.1-0.1 c0.2-0.2,0.3-0.4,0.3-0.6c0-0.2-0.1-0.5-0.2-0.6L63.3,50l-9.2-9.3l9.1-9.1c0.1-0.2,0.2-0.4,0.2-0.7c0-0.3-0.2-0.5-0.4-0.7 c-0.3-0.3-0.8-0.3-1.2,0l-9.3,9.1l-9.1-9.1c-0.4-0.3-1-0.2-1.4,0.2C41.8,30.7,41.8,31.2,42.1,31.6z"/></g></g></g></svg>
                                    </i> {{ trans('messages.blacklist') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
            <div class="navbar-right">
                <ul class="navbar-nav me-auto mb-md-0">
                    @include('layouts.core._top_activity_log')
                    
                    @include('layouts.core._menu_frontend_user')
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    var MenuFrontend = {
        saveLeftbarState: function(state) {
            var url = '{{ action('AccountController@leftbarState') }}';

            $.ajax({
                method: "GET",
                url: url,
                data: {
                    _token: CSRF_TOKEN,
                    state: state,
                }
            });
        }
    };

    $(function() {
        //
        $('.leftbar .leftbar-hide-menu').on('click', function(e) {
            if (!$('.leftbar').hasClass('leftbar-closed')) {
                $('.leftbar').addClass('leftbar-closed');

                MenuFrontend.saveLeftbarState('closed');
            } else {
                $('.leftbar').removeClass('leftbar-closed');

                MenuFrontend.saveLeftbarState('open');
            }
        });
    });        
</script>
