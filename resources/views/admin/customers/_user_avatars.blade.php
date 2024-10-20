<div class="d-flex align-items-center mb-1">
    <div class="text-nowrap">
        <a href="{{ action('Admin\UserController@index', [
            'customer_uid' => $customer->uid,
        ]) }}">
            <span>{{ $customer->users()->count() }}</span> {{ trans('messages.users') }}
        </a>
    </div>
    <div class="mx-2">|</div>
    <div>
        <div class="avatar-layers d-flex align-items-center">
            @foreach ($customer->users()->limit(5)->get() as $index => $user)
                <div class="avatar-layer" style="left: {{ $index*8 }}px">
                    <img src="{{ $user->getProfileImageUrl() }}" style="border-radius:100%" class="menu-user-avatar shadow-sm avatar-img" alt=""
                        style="
                            
                        "
                    >
                </div>
            @endforeach
        </div>
    </div>
</div>