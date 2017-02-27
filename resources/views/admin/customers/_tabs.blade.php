<div class="tabbable">
    <ul class="nav nav-tabs nav-tabs-top">
        <li class="
        {{ request()->route()->getActionName() == 'Acelle\Http\Controllers\Admin\CustomerController@edit' ? 'active' : '' }}
        text-semibold"><a href="{{ action('Admin\CustomerController@edit', $customer->uid) }}">
            <i class="icon-user"></i> {{ trans('messages.profile') }}</a>
        </li>
        <li class="
        {{ request()->route()->getActionName() == 'Acelle\Http\Controllers\Admin\CustomerController@subscriptions' ? 'active' : '' }}
        text-semibold"><a href="{{ action('Admin\CustomerController@subscriptions', $customer->uid) }}">
            <i class="icon-quill4"></i> {{ trans('messages.subscriptions') }}</a>
        </li>
    </ul>
</div>