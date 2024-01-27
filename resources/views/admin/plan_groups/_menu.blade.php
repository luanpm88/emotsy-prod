<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-top nav-underline">
            <li class="nav-item {{ $menu == 'general' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanGroupController@general', $planGroup->uid) }}" class="nav-link">
                    {{ trans('messages.plan_group.general') }}
                </a>
            </li>
            <li  class="nav-item dropdown {{ in_array($menu, ['quota','security','email_footer','tos']) ? 'active' : '' }}" >
                <a class="nav-link dropdown-toggle" href="" class="level-1" data-bs-toggle="dropdown">
                    {{ trans('messages.plan_group.settings') }}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="{{ $menu == 'quota' ? 'active' : '' }}">
                        <a class="dropdown-item" href="{{ action('Admin\PlanGroupController@quota', $planGroup->uid) }}">
                            {{ trans('messages.plan_group.quota') }}
                        </a>
                    </li>
                    <li class="{{ $menu == 'security' ? 'active' : '' }}">
                        <a class="dropdown-item" href="{{ action('Admin\PlanGroupController@security', $planGroup->uid) }}">
                            {{ trans('messages.plan_group.security') }}
                        </a>
                    </li>
                    <li class="{{ $menu == 'email_footer' ? 'active' : '' }}">
                        <a class="dropdown-item" href="{{ action('Admin\PlanGroupController@emailFooter', $planGroup->uid) }}">
                            {{ trans('messages.plan_group.email_footer') }}
                        </a>
                    </li>
                    <li class="{{ $menu == 'tos' ? 'active' : '' }}">
                        <a class="dropdown-item" href="{{ action('Admin\PlanGroupController@tos', $planGroup->uid) }}">
                            {{ trans('messages.plan_group.tos') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ $menu == 'sending_server' ? 'active' : '' }}">                
                @if ($planGroup->useSystemSendingServer() && !$planGroup->hasPrimarySendingServer())
                    <a href="{{ action('Admin\PlanGroupController@sendingServer', $planGroup->uid) }}" class="nav-link level-1 xtooltip position-relative"
                         title="{{ trans('messages.plan_group.send_via.empty') }}"
                    >
                        {{ trans('messages.plan_group.sending_server') }}
                        <i class="material-symbols-rounded tabs-warning-icon text-danger">info</i>
                    </a>
                @else
                    <a href="{{ action('Admin\PlanGroupController@sendingServer', $planGroup->uid) }}" class="nav-link nav-link">
                        {{ trans('messages.plan_group.sending_server') }}
                    </a>
                @endif
                
            </li>
            <li class="nav-item {{ $menu == 'email_verification' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanGroupController@emailVerification', $planGroup->uid) }}" class="nav-link nav-link">
                    {{ trans('messages.plan_group.email_verification') }}
                </a>
            </li>
        </ul>
    </div>
</div>
