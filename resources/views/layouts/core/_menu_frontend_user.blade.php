@php			
    $iconDir = "";
    if (Auth::user()->customer->getColorScheme() == 'white') {
        $iconDir = "dark/";
    }
@endphp

<li class="nav-item dropdown">
    <a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center ps-3 pe-1 py-3 lvl-1 dropdown-toggle"
        id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="navbar-icon">
            <img src="{{ Auth::user()->getProfileImageUrl() }}" style="border-radius:100%;transform:scale(1.5)" alt="">
        </i>
        <span>{{ Auth::user()->displayName() }}</span>
        @if (Auth::user()->customer->hasSubscriptionNotice())
            <i class="material-icons-outlined customer-warning-icon text-danger" style="right:2px;color:rgb(236, 124, 124)!important;top:2px;position:absolute">info</i>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="content-menu">
        @can("admin_access", Auth::user())
            <li class="border-bottom"><a href="{{ action("Admin\HomeController@index") }}" class="dropdown-item d-flex align-items-center">
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
        <li class="nav-item" rel0="AccountSubscriptionController/index">
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
        <li class="nav-item" rel0="AccountController/billing"><a href="{{ action("AccountController@billing") }}" class="dropdown-item d-flex align-items-center">
            <i class="navbar-icon mr-3">
                <img src="{{ url('images/icons/'.$iconDir.'SVG/billing.svg') }}" />
            </i>{{ trans('messages.billing') }}
        
        </a></li>
        <li class="nav-item" rel0="AccountController/profile"><a href="{{ action("AccountController@profile") }}" class="dropdown-item d-flex align-items-center">
            <i class="navbar-icon mr-3">
                <img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
            </i>{{ trans('messages.account') }}
        
        </a></li>
        @if (Auth::user()->customer->canUseApi())
            <li class="nav-item" rel0="AccountController/api">
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