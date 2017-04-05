@if ($admin)
    <span class="text-muted admin-line"><i class="icon-user-tie"></i> {{ $admin->displayName() }}</span>
@else
    <span class="text-muted admin-line"><i class="icon-user-tie"></i> {{ trans('messages.no_admin') }}</span>
@endif
