<nav class="navbar navbar-expand-xl navbar-dark fixed-top navbar-main py-0">
    <div class="container-fluid ms-0">
        <a class="navbar-brand d-flex align-items-center me-2" href="{{ action('HomeController@index') }}">
            @if (\Acelle\Model\Setting::get('site_logo_small'))
                <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
            @else
                <img class="logo" src="{{ URL::asset('images/logo-' . (Auth::user()->customer->getColorScheme() == "white" ? "dark" : "light") . '.svg') }}" alt="">
            @endif
        </a>
        <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="leftbar-hide-menu">
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
                    <a href="{{ action('HomeController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img src="{{ url('images/icons/'.$iconDir.'SVG/home.svg') }}" />
                        </i>
                        <span>{{ trans('messages.dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="CampaignController">
                    <a href="{{ action('CampaignController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img src="{{ url('images/icons/'.$iconDir.'SVG/campaign.svg') }}" />
                        </i>
                        <span>{{ trans('messages.campaigns') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="Automation2Controller">
                    <a href="{{ action('Automation2Controller@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/automation.svg') }}" />
                        </i>
                        <span>{{ trans('messages.automations') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="MailListController">
                    <a href="{{ action('MailListController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img style="width: 21px" src="{{ url('images/icons/'.$iconDir.'SVG/list.svg') }}" />
                        </i>
                        <span>{{ trans('messages.lists') }}</span>
                    </a>
                </li>
                <li class="nav-item" rel0="TemplateController">
                    <a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
                        <i class="navbar-icon">
                            <img style="width: 18px" src="{{ url('images/icons/'.$iconDir.'SVG/template.svg') }}" />
                        </i>
                        <span>{{ trans('messages.templates') }}</span>
                    </a>
                </li>
                @if (config('custom.woo'))
                <li class="nav-item dropdown">
                    <a href=""
                        class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle"
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
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/product-icon.svg') }}" />
                                </i>
                                <span>{{ trans('messages.products') }}</span>
                            </a>
                        </li>
                        <li class="nav-item" rel0="SourceController">
                            <a class="dropdown-item d-flex align-items-center" href="{{ action('SourceController@index') }}">
                                <i class="navbar-icon" style="">
                                    <img style="width: 18px" src="{{ url('images/icons/'.$iconDir.'SVG/store.svg') }}" />
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
                        <a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="sending-menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="navbar-icon">
                                <img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/sending.svg') }}" />
                            </i>
                            <span>{{ trans('messages.sending') }}</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="sending-menu">
                            @if (Auth::user()->customer->can("read", new Acelle\Model\SendingServer()))
                                <li class="nav-item" rel0="SendingServerController">
                                    <a href="{{ action('SendingServerController@index') }}"
                                        class="dropdown-item d-flex align-items-center">
                                        <i class="navbar-icon mr-2" style="width:19px">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
                                    </i> {{ trans('messages.sending_servers') }}
                                    </a>
                                </li>
                            @endif
                            <li rel1="SendingDomainController">
                                <a href="{{ action('SendingDomainController@index') }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-2" style="width:20	px">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/domain.svg') }}" />
                                    </i> {{ trans('messages.sending_domains') }}
                                </a>
                            </li>
                            <li class="nav-item" rel0="SenderController">
                                <a href="{{ action('SenderController@index') }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-2" style="width:20	px">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/identify.svg') }}" />
                                    </i> {{ trans('messages.verified_senders') }}
                                </a>
                            </li>
                            <li class="nav-item" rel0="TrackingDomainController">
                                <a href="{{ action('TrackingDomainController@index') }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-2" style="width:20px">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/domain.svg') }}" />
                                    </i> {{ trans('messages.tracking_domains') }}
                                </a>
                            </li>
                            @if (Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()))
                                <li class="nav-item" rel0="EmailVerificationServerController">
                                    <a href="{{ action('EmailVerificationServerController@index') }}" class="dropdown-item d-flex align-items-center">
                                    <i class="navbar-icon mr-2" style="width:20px">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
                                    </i> {{ trans('messages.email_verification_servers') }}
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->customer->can("read", new Acelle\Model\Blacklist()))
                                <li class="nav-item" rel0="BlacklistController">
                                    <a href="{{ action('BlacklistController@index') }}" class="dropdown-item d-flex align-items-center">
                                    <i class="navbar-icon mr-2" style="width:20px">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/blacklist.svg') }}" />
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