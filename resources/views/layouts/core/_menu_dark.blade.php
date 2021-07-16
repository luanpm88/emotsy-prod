<nav class="navbar navbar-expand-xl navbar-dark bg-dark fixed-top navbar-main py-0">
    <div class="container-fluid ms-0">
        <a class="navbar-brand d-flex align-items-center me-2" href="{{ action('HomeController@index') }}">
            @if (\Acelle\Model\Setting::get('site_logo_small'))
                <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
            @else
                <img style="height: 18px" class="logo" src="{{ URL::asset('images/logo_light_blue.svg') }}" alt="">
            @endif
        </a>
        <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        @php			
            $iconDir = "";
            if (Auth::user()->customer->getColorScheme() == 'white') {
                $iconDir = "dark/";
            }
        @endphp

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-md-0">
                <li class="nav-item" rel0="HomeController">
                    @yield('menu_title')
                </li>
            </ul>
            <div class="navbar-right">
                <ul class="navbar-nav me-auto mb-md-0">
                    <li class="d-flex align-items-center">
                        @yield('menu_right')
                    </li>
                    <li class="nav-item dropdown">
                        <a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="navbar-icon">
                                <img src="{{ Auth::user()->getProfileImageUrl() }}" style="border-radius:100%;transform:scale(1.5)" alt="">
                            </i>
                            <span>{{ Auth::user()->displayName() }}</span>
                            @if (Auth::user()->customer->hasSubscriptionNotice())
                                <i class="material-icons-round customer-warning-icon text-danger" style="right:2px;color:red!important;top:2px;position:absolute">info</i>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="content-menu">
                            @can("admin_access", Auth::user())
                                <li rel0="TemplateController"><a href="{{ action("Admin\HomeController@index") }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-3">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/changeview.svg') }}" />
                                </i>{{ trans('messages.admin_view') }}</a></li>
                                <li class="divider"></li>
                            @endif
                            @if (request()->user()->customer->activeSubscription())
                                <li class="dropdown">
                                    <a href="#" class="dropdown-item top-quota-button d-flex align-items-center" data-url="{{ action("AccountController@quotaLog") }}">
                                    <i class="navbar-icon mr-3">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/sreport.svg') }}" />
                                </i>
                                        <span class="">{{ trans('messages.used_quota') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li rel0="AccountSubscriptionController/index">
                                <a href="{{ action('AccountSubscriptionController@index') }}" class="dropdown-item position-relative d-flex align-items-center">
                                <i class="navbar-icon mr-3">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/subscription.svg') }}" />
                                </i>{{ trans('messages.subscriptions') }}
                                    @if (Auth::user()->customer->hasSubscriptionNotice())
                                        <i class="material-icons-outlined subscription-warning-icon text-danger" style="font-size: inherit;
                                            position: absolute;
                                            right: 0;
                                            top: 0;">info</i>
                                    @endif
                                </a>
                            </li>
                            <li rel0="AccountController/billing"><a href="{{ action("AccountController@billing") }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-3">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/billing.svg') }}" />
                                </i>{{ trans('messages.billing') }}
                            
                            </a></li>
                            <li rel0="AccountController/profile"><a href="{{ action("AccountController@profile") }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-3">
                                    <img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
                                </i>{{ trans('messages.account') }}
                            
                            </a></li>
                            @if (Auth::user()->customer->canUseApi())
                                <li rel0="AccountController/api">
                                    <a href="{{ action("AccountController@api") }}" class="dropdown-item d-flex align-items-center">
                                    <i class="navbar-icon mr-3">
                                    <img style="transform:scale(1.15)" src="{{ url('images/icons/'.$iconDir.'SVG/api.svg') }}" />
                                </i>{{ trans('messages.campaign_api') }}
                                    </a>
                                </li>
                            @endif
                            <li><a href="{{ url("/logout") }}" class="dropdown-item d-flex align-items-center">
                                <i class="navbar-icon mr-3">
                                    <img style="transform:scale(.95)" src="{{ url('images/icons/'.$iconDir.'SVG/logout.svg') }}" />
                                </i>{{ trans('messages.logout') }}
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>