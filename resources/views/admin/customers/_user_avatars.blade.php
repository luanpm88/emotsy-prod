<div class="d-flex align-items-center mb-1">
    <div>
        <div class="avatar-layers d-flex align-items-center">
            @foreach ($customer->users()->limit(5)->get() as $index => $user)
                <a href="{{ action('Admin\UserController@edit', [
                        'customer_uid' => $customer->uid,
                        "user" => $user->uid,
                    ]) }}" class="avatar-layer xtooltip" style="left: {{ $index*20 }}px"
                    title="{{ $user->displayName(get_localization_config('show_last_name_first', Auth::user()->admin->getLanguageCode())) }}"
                >
                    <img src="{{ $user->getProfileImageUrl() }}" style="border-radius:100%" class="menu-user-avatar avatar-img" alt=""
                        style="
                            
                        "
                    >
                </a>
            @endforeach
        </div>
    </div>
    <div class="mx-2">|</div>
    <div class="text-nowrap">
        <a href="{{ action('Admin\UserController@index', [
            'customer_uid' => $customer->uid,
        ]) }}">
            <span>{{ $customer->users()->count() }}</span> {{ trans('messages.users') }}
        </a>
    </div>
</div>