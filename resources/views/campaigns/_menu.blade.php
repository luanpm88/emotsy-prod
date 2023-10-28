<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs nav-tabs-top nav-underline">
			<li class="nav-item {{ $menu == 'overview' ? 'active' : '' }}">
				<a href="{{ action('CampaignController@overview', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">auto_graph</span> {{ trans('messages.overview') }}
				</a>
			</li>
			<li class="nav-item {{ $menu == 'links' ? 'active' : '' }}">
				<a href="{{ action('CampaignController@links', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">link</span> {{ trans('messages.links') }}
				</a>
			</li>
			<li class="nav-item {{ $menu == 'open_map' ? 'active' : '' }}">
				<a href="{{ action('CampaignController@openMap', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">map</span> {{ trans('messages.open_map') }}
				</a>
			</li>
			<li class="nav-item {{ $menu == 'subscribers' ? 'active' : '' }}">
				<a href="{{ action('CampaignController@subscribers', $campaign->uid) }}" class="nav-link">
					<span class="material-symbols-rounded">people_outline</span> {{ trans('messages.subscribers') }}
				</a>
			</li>
			<li class="nav-item {{ in_array($menu, [
				'tracking_log',
				'bounce_log',
				'feedback_log',
				'open_log',
				'click_log',
				'unsubscribe_log',
			]) ? 'active' : '' }}">
				<a href="{{ action("AccountController@contact") }}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
					<span class="material-symbols-rounded">article</span> {{ trans('messages.sending_logs') }}
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li class="{{ $menu == 'tracking_log' ? 'active' : '' }}">
						<a class="dropdown-item" href="{{ action('CampaignController@trackingLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">article</span> {{ trans('messages.tracking_log') }}
						</a>
					</li>
					<li class="{{ $menu == 'bounce_log' ? 'active' : '' }}">
						<a class="dropdown-item" href="{{ action('CampaignController@bounceLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">article</span> {{ trans('messages.bounce_log') }}
						</a>
					</li>
					<li class="{{ $menu == 'feedback_log' ? 'active' : '' }}">
						<a class="dropdown-item" href="{{ action('CampaignController@feedbackLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">article</span> {{ trans('messages.feedback_log') }}
						</a>
					</li>
					<li class="{{ $menu == 'open_log' ? 'active' : '' }}">
						<a class="dropdown-item" href="{{ action('CampaignController@openLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">article</span> {{ trans('messages.open_log') }}
						</a>
					</li>
					<li class="{{ $menu == 'click_log' ? 'active' : '' }}">
						<a class="dropdown-item" href="{{ action('CampaignController@clickLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">article</span> {{ trans('messages.click_log') }}
						</a>
					</li>
					<li class="{{ $menu == 'unsubscribe_log' ? 'active' : '' }}">
						<a class="dropdown-item" href="{{ action('CampaignController@unsubscribeLog', $campaign->uid) }}">
							<span class="material-symbols-rounded">article</span> {{ trans('messages.unsubscribe_log') }}
						</a>
					</li>
				</ul>
			</li>
			<li class="nav-item {{ $menu == 'template' ? 'active' : '' }}">
				<a href="javascript:;" onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)" class="nav-link">
					<span class="material-symbols-rounded">auto_awesome_mosaic</span> {{ trans('messages.email_review') }}
				</a>
			</li>
			@if ($campaign->google_analytic_link_tracking)
				<li class="nav-item {{ $menu == 'google_analytics' ? 'active' : '' }}">
					<a href="{{ action("GoogleAnalyticsController@googleAnalytics", $campaign->uid) }}" class="nav-link">
						<svg style="height: 16px;
						display: inline-block;
						line-height: inherit;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
						{{ trans('messages.google_analytics') }}
					</a>
				</li>
			@endif
		</ul>
	</div>
</div>

<script>
	var downloaded = false;
	var downloadWindow;

	function goToDownLoad(logtype) {
		downloadWindow = window.open('{{ action('CampaignController@trackingLogDownload', ['uid' => $campaign->uid]) }}?logtype=' + logtype, '_blank');
	}

	function downloadAndCloseDownloadWindow(downloadUrl) {
		downloadWindow.close();
		window.location.href = downloadUrl;
	}
</script>
