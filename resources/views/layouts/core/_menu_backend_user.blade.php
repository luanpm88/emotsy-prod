@php			
    $iconDir = "";
    if (Auth::user()->admin->getColorScheme() == 'white') {
        $iconDir = "dark/";
    }
@endphp

<li class="nav-item dropdown">
    <a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center ps-3 pe-1 py-3 lvl-1 dropdown-toggle"
        id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="navbar-icon">
            <img src="{{ Auth::user()->getProfileImageUrl() }}" style="border-radius:100%;transform:scale(1.5)" class="menu-user-avatar" alt="">
        </i>
        <span>{{ Auth::user()->displayName() }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-bottom" aria-labelledby="content-menu">
        @can("customer_access", Auth::user())
            <li class="border-bottom"><a href="{{ action("HomeController@index") }}" class="dropdown-item d-flex align-items-center">
            <i class="acelle-icon mr-2">
                <img src="{{ url('images/icons/'.$iconDir.'SVG/changeview.svg') }}" />
            </i>{{ trans('messages.customer_view') }}
            </a></li>
            <li class="divider"></li>
        @endif
        <li><a href="{{ action("Admin\AccountController@profile") }}" class="dropdown-item d-flex align-items-center">
        <i class="acelle-icon mr-2">
                <img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
            </i>{{ trans('messages.account') }}
        </a></li>
        <li class="nav-item" rel0="AccountController/api">
            <a href="{{ action("Admin\AccountController@api") }}" class="dropdown-item d-flex align-items-center">
            <i class="acelle-icon mr-2">
                <img src="{{ url('images/icons/'.$iconDir.'SVG/api.svg') }}" />
            </i>{{ trans('messages.admin_api') }}
            </a>
        </li>
        <li><a href="{{ url("/logout") }}" class="dropdown-item d-flex align-items-center">
            <i class="acelle-icon mr-2">
                <img style="height:19px;margin-top: 1px;" class="" src="{{ url('images/icons/'.$iconDir.'SVG/logout.svg') }}" />
            </i>{{ trans('messages.logout') }}</a></li>
    </ul>
</li>