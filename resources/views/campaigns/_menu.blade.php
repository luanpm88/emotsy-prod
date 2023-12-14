<div class="row">
	<div class="col-md-12">
		<div class="d-flex align-items-center">
			<ul class="nav nav-tabs nav-tabs-top nav-underline border-bottom-0 mb-0">
				<li class="nav-item {{ $menu == 'overview' ? 'active' : '' }}">
					<a href="{{ action('CampaignController@overview', [
						'uid' => $campaign->uid,
						'version_b' => request()->version_b,
					]) }}" class="nav-link">
						<span class="material-symbols-rounded">auto_graph</span> {{ trans('messages.overview') }}
					</a>
				</li>
				@if ($campaign->isABTest())
					<li class="nav-item {{ $menu == 'ab-test' ? 'active' : '' }}">
						<a href="{{ action('CampaignController@abTestInsights', [
							'uid' => $campaign->uid,
							'version_b' => request()->version_b,
						]) }}" class="nav-link">
							<span class="material-symbols-rounded">key_visualizer</span> {{ trans('messages.campaign.ab.test_insights') }}
						</a>
					</li>
				@endif
				<li class="nav-item {{ $menu == 'links' ? 'active' : '' }}">
					<a href="{{ action('CampaignController@links', [
						'uid' => $campaign->uid,
						'version_b' => request()->version_b,
					]) }}" class="nav-link">
						<span class="material-symbols-rounded">link</span> {{ trans('messages.links') }}
					</a>
				</li>
				<li class="nav-item {{ $menu == 'open_map' ? 'active' : '' }}">
					<a href="{{ action('CampaignController@openMap', [
						'uid' => $campaign->uid,
						'version_b' => request()->version_b,
					]) }}" class="nav-link">
						<span class="material-symbols-rounded">map</span> {{ trans('messages.open_map') }}
					</a>
				</li>
				<li class="nav-item {{ $menu == 'subscribers' ? 'active' : '' }}">
					<a href="{{ action('CampaignController@subscribers', [
						'uid' => $campaign->uid,
						'version_b' => request()->version_b,
					]) }}" class="nav-link">
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
							<a class="dropdown-item" href="{{ action('CampaignController@trackingLog', [
								'uid' => $campaign->uid,
								'version_b' => request()->version_b,
							]) }}">
								<span class="material-symbols-rounded">article</span> {{ trans('messages.tracking_log') }}
							</a>
						</li>
						<li class="{{ $menu == 'bounce_log' ? 'active' : '' }}">
							<a class="dropdown-item" href="{{ action('CampaignController@bounceLog', [
								'uid' => $campaign->uid,
								'version_b' => request()->version_b,
							]) }}">
								<span class="material-symbols-rounded">article</span> {{ trans('messages.bounce_log') }}
							</a>
						</li>
						<li class="{{ $menu == 'feedback_log' ? 'active' : '' }}">
							<a class="dropdown-item" href="{{ action('CampaignController@feedbackLog', [
								'uid' => $campaign->uid,
								'version_b' => request()->version_b,
							]) }}">
								<span class="material-symbols-rounded">article</span> {{ trans('messages.feedback_log') }}
							</a>
						</li>
						<li class="{{ $menu == 'open_log' ? 'active' : '' }}">
							<a class="dropdown-item" href="{{ action('CampaignController@openLog', [
								'uid' => $campaign->uid,
								'version_b' => request()->version_b,
							]) }}">
								<span class="material-symbols-rounded">article</span> {{ trans('messages.open_log') }}
							</a>
						</li>
						<li class="{{ $menu == 'click_log' ? 'active' : '' }}">
							<a class="dropdown-item" href="{{ action('CampaignController@clickLog', [
								'uid' => $campaign->uid,
								'version_b' => request()->version_b,
							]) }}">
								<span class="material-symbols-rounded">article</span> {{ trans('messages.click_log') }}
							</a>
						</li>
						<li class="{{ $menu == 'unsubscribe_log' ? 'active' : '' }}">
							<a class="dropdown-item" href="{{ action('CampaignController@unsubscribeLog', [
								'uid' => $campaign->uid,
								'version_b' => request()->version_b,
							]) }}">
								<span class="material-symbols-rounded">article</span> {{ trans('messages.unsubscribe_log') }}
							</a>
						</li>
					</ul>
				</li>
				{{-- <li class="nav-item {{ $menu == 'template' ? 'active' : '' }}">
					<a href="javascript:;" onclick="popupwindow('{{ action('CampaignController@preview', [
						'uid' => $campaign->uid,
						'version_b' => request()->version_b,
					]) }}', `{{ $campaign->name }}`, 800)" class="nav-link">
						<span class="material-symbols-rounded">auto_awesome_mosaic</span> {{ trans('messages.email_review') }}
					</a>
				</li> --}}
			</ul>
		</div>
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
