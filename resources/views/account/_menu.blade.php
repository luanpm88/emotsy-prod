@php $menu = $menu ?? false @endphp

<div class="row">
    <div class="col-md-12">
        <div class="tabbable pb-2">
            <ul class="nav nav-tabs nav-tabs-top nav-underline mb-4">
                <li class="nav-item {{ $menu == 'profile' ? 'active' : '' }}">
                    <a href="{{ action("AccountController@profile") }}" class="nav-link">
                        <span class="material-symbols-rounded">face</span> {{ trans('messages.my_profile') }}
                    </a>
                </li>
                <li class="nav-item {{ $menu == 'contact' ? 'active' : '' }}">
                    <a href="{{ action("AccountController@contact") }}" class="nav-link">
                        <span class="material-symbols-rounded">maps_home_work</span> {{ trans('messages.contact_information') }}
                    </a>
                </li>
                @if (Auth::user()->hasPermission('account.full_access'))
                    <li class="nav-item {{ $menu == 'users' ? 'active' : '' }}">
                        <a href="{{ action("UserController@index") }}" class="nav-link">
                            <span class="material-symbols-rounded">group</span> {{ trans('messages.users') }}
                        </a>
                    </li>
                    <li class="nav-item {{ $menu == 'role' ? 'active' : '' }}">
                        <a href="{{ action("RoleController@index") }}" class="nav-link">
                            <span class="material-symbols-rounded">admin_panel_settings</span> {{ trans('messages.roles') }}
                        </a>
                    </li>
                @endif
                @if (config('app.saas'))
                    <li class="nav-item {{ $menu == 'subscription' ? 'active' : '' }}">
                        <a href="{{ action("SubscriptionController@index") }}" class="nav-link position-relative {{ isset($tab) && $tab == 'subscription' ? 'active' : '' }}">
                            <span class="material-symbols-rounded">auto_mode</span> {{ trans('messages.subscription_and_credits') }}
                            @if (Auth::user()->customer->hasSubscriptionNotice())
                                <i class="material-symbols-rounded tabs-warning-icon text-danger">info</i>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{ $menu == 'billing' ? 'active' : '' }}">
                        <a href="{{ action("AccountController@billing") }}" class="nav-link">
                            <span class="material-symbols-rounded">credit_card</span> {{ trans('messages.billing') }}
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ $menu == 'log' ? 'active' : '' }}">
                    <a href="{{ action("AccountController@logs") }}" class="nav-link">
                        <span class="material-symbols-rounded">restore</span> {{ trans('messages.logs') }}
                    </a>
                </li>
                <li class="nav-item {{ $menu == 'api' ? 'active' : '' }}">
                    <a href="{{ action("AccountController@api") }}" class="nav-link">
                        <span class="material-symbols-rounded">vpn_key</span> {{ trans('messages.api_token') }}
                    </a>
                </li>
                <li class="nav-item {{ $menu == 'chatgpt' ? 'active' : '' }}">
                    <a href="{{ action("ChatgptController@settings") }}" class="nav-link">
                        <span class="material-symbols-rounded">psychology</span> {{ trans('messages.account.chatgpt') }}
                    </a>
                </li>
                {{-- <li class="nav-item {{ $menu == 'google_analytics' ? 'active' : '' }}">
                    <a style="line-height:24px;" href="{{ action("GoogleAnalyticsController@index") }}" class="nav-link">
                        <svg style="height: 16px;
                        display: inline-block;
                        line-height: inherit;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
                        {{ trans('messages.google_analytics') }}
                    </a>
                </li> --}}
                @if (\Acelle\Model\Setting::get('2fa_enable') == 'yes')
                    <li class="nav-item {{ $menu == '2fa' ? 'active' : '' }}">
                        <a href="{{ action("TwoFAController@index") }}" class="nav-link">
                            <span class="material-symbols-rounded">security</span> {{ trans('messages.2fa.two_factor_authentication') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
