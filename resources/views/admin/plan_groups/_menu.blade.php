<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-top nav-underline">
            <li class="nav-item {{ $menu == 'general' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanGroupController@general', $planGroup->uid) }}" class="nav-link">
                    {{ trans('messages.plan_group.general') }}
                </a>
            </li>
            <li class="nav-item {{ $menu == 'features' ? 'active' : '' }}">
                <a href="{{ action('Admin\PlanGroupController@features', $planGroup->uid) }}" class="nav-link">
                    {{ trans('messages.plan_group.features') }}
                </a>
            </li>
            <li class="nav-item {{ $menu == 'email_footer' ? 'active' : '' }}">
                <a class="nav-link" href="{{ action('Admin\PlanGroupController@emailFooter', $planGroup->uid) }}">
                    {{ trans('messages.plan_group.email_footer') }}
                </a>
            </li>
            <li class="nav-item {{ $menu == 'tos' ? 'active' : '' }}">
                <a class="nav-link" href="{{ action('Admin\PlanGroupController@tos', $planGroup->uid) }}">
                    {{ trans('messages.plan_group.tos') }}
                </a>
            </li>
        </ul>
    </div>
</div>
