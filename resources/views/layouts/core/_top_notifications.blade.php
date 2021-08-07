<li class="nav-item dropdown me-3">
	<a href="{{ action("Admin\NotificationController@index") }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
		<i class="navbar-icon me-1 top-notification-icon">
			<svg class="NavIcon SidebarTopNavLinks-typeIcon BellNavIcon" viewBox="0 0 40 40"><path d="M7.5,32L7.5,32h-1c-1.5,0-2.8-0.8-3.4-2c-0.8-1.5-0.4-3.4,0.9-4.5c1.2-1,1.9-2.4,2-3.9v-6.1C6,8.1,12.3,2,20,2s14,6.1,14,13.5V22c0.2,1.4,0.9,2.6,2,3.5c1.3,1.1,1.7,2.9,0.9,4.5c-0.6,1.2-2,2-3.4,2h-0.9H7.5z M7.6,29h25.8c0.3,0,0.7-0.2,0.8-0.4c0.2-0.4,0-0.7-0.2-0.8l0,0c-1.6-1.4-2.7-3.3-3-5.5c0-0.1,0-0.1,0-0.2v-6.6C31,9.7,26.1,5,20,5S9,9.7,9,15.5v6.1v0.1c-0.2,2.4-1.3,4.5-3.1,6c-0.2,0.2-0.3,0.5-0.2,0.8C5.9,28.8,6.2,29,6.5,29H7.6L7.6,29z M24.7,34c-0.7,1.9-2.5,3.2-4.7,3.2s-4-1.3-4.7-3.2H24.7z"></path></svg>
		</i>
		@if (Acelle\Model\Notification::count())
			<i class="top-notification-icon-dot"></i>
		@endif
	</a>
	<ul class="dropdown-menu fix-drop-down dropdown-menu-end log-menu-dropdown activity_logs" aria-labelledby="content-menu">
		<li class="activity_log_header">
			<a class="dropdown-item fw-bold" href="{{ action("Admin\NotificationController@index") }}">
				<div class="text-muted">
					{{ trans('messages.notifications') }}
				</div>
			</a>
		</li>
		@if (Auth::user()->customer && Auth::user()->customer->logs()->count() == 0)
			<li>
				<a class="dropdown-item text-center" href="{{ action("Admin\NotificationController@index") }}">
					<div class="material-icons-outlined fs-3 text-muted mb-2">
						running_with_errors
					</div>
					<div class="text-muted">
						{{ trans('messages.no_activity_logs') }}
					</div>
				</a>
			</li>
		@endif
		@foreach (Auth::user()->admin->notifications()->take(20)->get() as $notification)
			<li>
				<div class="dropdown-item d-flex align-items-center border-top" hrxef="javascript:;">
					<i class="navbar-icon me-3" style="width:30px">
						@if ($notification->level == \Acelle\Model\Notification::LEVEL_WARNING)
						<span class="material-icons-round bg-warning notification-icon">
							warning_amber
							</span>
						@elseif ( false &&$notification->level == \Acelle\Model\Notification::LEVEL_ERROR)
						<span class="material-icons-outlined bg-danger notification-icon">
							new_releases
							</span>
						@else
						<span class="material-icons-outlined bg-info notification-icon">
							lightbulb
							</span>
						@endif
					</i>
					<span class="d-block" style="width: 255px;">
						<span class="d-flex mb-0">
							<span class="text-semibold me-auto fw-600" style="white-space: initial;">{{ $notification->title }}</span>
							<span class="text-muted small"><span class="material-icons-outlined">
								restore
								</span> {{ $notification->created_at->diffForHumans() }}</span>
						</span>
						<span class="desc-menu-log text-muted2 small">{{ $notification->message }}</span>
					</span>
				</div>
			</li>
		@endforeach
		<li>
			<a class="dropdown-item d-flex justify-content-center border-top" href="{{ action("Admin\NotificationController@index") }}" data-popup="tooltip" title="{{ trans('messages.all_logs') }}">
				<span class="material-icons-round fs-3 my-0" style="line-height: 20px">
					more_horiz
				</span>
			</a>
		</li>
	</ul>
</li>