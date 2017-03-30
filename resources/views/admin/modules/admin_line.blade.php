@if ($admin)
    <span class="text-muted"><i class="icon-user-tie"></i> {{ $admin->displayName() }}</span>
@else
    <span class="text-muted"><i class="icon-user-tie"></i> {{ trans('messages.none') }}</span>
@endif
