@if (\Acelle\Model\Setting::isYes('theme.beta'))
    @include('auth/t_login')
@elseif (config('app.emotsy'))
    @include('emotsy.login')
@else
    @include('auth/b_login')
@endif
