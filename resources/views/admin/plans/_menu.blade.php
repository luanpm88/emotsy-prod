<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-top nav-underline">
            <li class="nav-item {{ $menu == 'general' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanController@general', $plan->uid) }}" class="nav-link">
                    {{ trans('messages.plan.general') }}
                </a>
            </li>
            <li class="nav-item {{ $menu == 'quota' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanController@quota', $plan->uid) }}" class="nav-link">
                    {{ trans('messages.plan.quota') }}
                </a>
            </li>
            <li class="nav-item {{ $menu == 'security' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanController@security', $plan->uid) }}" class="nav-link">
                    {{ trans('messages.plan.speed_limit') }}
                </a>
            </li>
            <li class="nav-item {{ $menu == 'sending_server' ? 'active' : '' }}">                
                @if ($plan->useSystemSendingServer() && !$plan->hasPrimarySendingServer())
                    <a href="{{ action('Admin\PlanController@sendingServer', $plan->uid) }}" class="nav-link level-1 xtooltip position-relative"
                         title="{{ trans('messages.plans.send_via.empty') }}"
                    >
                        {{ trans('messages.plan.sending_server') }}
                        <i class="material-symbols-rounded tabs-warning-icon text-danger">info</i>
                    </a>
                @else
                    <a href="{{ action('Admin\PlanController@sendingServer', $plan->uid) }}" class="nav-link nav-link">
                        {{ trans('messages.plan.sending_server') }}
                    </a>
                @endif
                
            </li>
            <li class="nav-item {{ $menu == 'email_verification' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanController@emailVerification', $plan->uid) }}" class="nav-link nav-link">
                    {{ trans('messages.plan.email_verification') }}
                </a>
            </li>
        </ul>
    </div>
</div>
