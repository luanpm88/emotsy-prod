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
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-bottom top-user-menus" aria-labelledby="content-menu">
        <li class="backdrop p-4 d-flex align-items-center justify-content-center">
            <img src="{{ url('images/sign-stand.svg') }}" width="80px" />
        </li>
        <li class="p-3" style="width: 300px">
            <div class="d-flex mb-2">
                <div class="big-avatar me-3">
                    <img width="80px" class="" src="{{ Auth::user()->getProfileImageUrl() }}"
                        style="border-radius:100%"
                        class="menu-user-avatar" alt="">
                </div>
                <div>
                    <label class="fs-6 fw-600 d-flex align-items-center">
                        <span class="me-3">{{ Auth::user()->displayName() }}</span>
                    </label>
                    <p class="mb-1 small text-muted">{{ Auth::user()->email }}</p>
                    @if (Auth::user()->admin->status) 
                        <span class="label label-flat bg-{{ Auth::user()->admin->status }}">{{ trans('messages.' . Auth::user()->admin->status) }}</span>
                    @endif
                </div>
            </div>
        </li>
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