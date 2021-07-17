<li class="nav-item dropdown me-3">
	<a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
		<i class="navbar-icon me-1">
			<span class="material-icons-round" style="transform: scale(1.7)">
				notifications_none
				</span>
		</i>
		@if (Acelle\Model\Notification::count())
			<i class="material-icons-outlined tabs-warning-icon text-danger top-notification-alert">info</i>
		@endif
	</a>
	<ul class="dropdown-menu fix-drop-down dropdown-menu-end log-menu-dropdown activity_logs" aria-labelledby="content-menu">
		<li class="activity_log_header">
			<a class="dropdown-item fw-bold" href="{{ action("AccountController@logs") }}">
				<div class="text-muted">
					{{ trans('messages.notifications') }}
				</div>
			</a>
		</li>
		@if (Auth::user()->customer->logs()->count() == 0)
			<li>
				<a class="dropdown-item text-center" href="{{ action("AccountController@logs") }}">
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
					<span class="d-block" style="width: 100%;">
						<span class="d-flex mb-0">
							<span class="text-semibold me-auto fw-600" style="white-space: initial;">{{ $notification->title }}</span>
							<span class="text-muted small"><span class="material-icons-outlined">
								restore
								</span> {{ $notification->created_at->diffForHumans() }}</span>
						</span>
						<span class="desc-menu-log text-muted2 small">{!! $notification->message !!}</span>
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