<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs nav-tabs-top page-second-nav">
			<li rel0="CampaignController/overview" class="dropdown">
				<a href="{{ action('CampaignController@overview', $campaign->uid) }}" class="level-1">
					<i class="icon-stats-bars3"></i> {{ trans('messages.overview') }}
				</a>
			</li>
			<li rel0="CampaignController/links" class="dropdown">
				<a href="{{ action('CampaignController@links', $campaign->uid) }}" class="level-1">
					<i class="icon-link"></i> {{ trans('messages.links') }}
				</a>
			</li>
			<li rel0="CampaignController/openMap" class="dropdown">
				<a href="{{ action('CampaignController@openMap', $campaign->uid) }}" class="level-1">
					<i class="icon-map4"></i> {{ trans('messages.open_map') }}
				</a>
			</li>
			<li rel0="CampaignController/subscribers" class="dropdown">
				<a href="{{ action('CampaignController@subscribers', $campaign->uid) }}" class="level-1">
					<span class="material-icons-round">
people_outline
</span> {{ trans('messages.subscribers') }}
				</a>
			</li>
			<li class="dropdown"
				rel0="CampaignController/trackingLog"
				rel1="CampaignController/bounceLog"
				rel2="CampaignController/feedbackLog"
				rel3="CampaignController/openLog"
				rel4="CampaignController/clickLog"
				rel5="CampaignController/unsubscribeLog"
			>
				<a href="{{ action("AccountController@contact") }}" class="level-1" data-bs-toggle="dropdown">
					<i class="icon-file-text2 position-left"></i> {{ trans('messages.sending_logs') }}
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li rel0="CampaignController/trackingLog" class="dropdown">
						<a href="{{ action('CampaignController@trackingLog', $campaign->uid) }}">
							<span class="material-icons-outlined">
auto_awesome
</span> {{ trans('messages.tracking_log') }}
						</a>
					</li>
					<li rel0="CampaignController/bounceLog" class="dropdown">
						<a href="{{ action('CampaignController@bounceLog', $campaign->uid) }}">
							<span class="material-icons-outlined">
auto_awesome
</span> {{ trans('messages.bounce_log') }}
						</a>
					</li>
					<li rel0="CampaignController/feedbackLog" class="dropdown">
						<a href="{{ action('CampaignController@feedbackLog', $campaign->uid) }}">
							<span class="material-icons-outlined">
auto_awesome
</span> {{ trans('messages.feedback_log') }}
						</a>
					</li>
					<li rel0="CampaignController/openLog" class="dropdown">
						<a href="{{ action('CampaignController@openLog', $campaign->uid) }}">
							<span class="material-icons-outlined">
auto_awesome
</span> {{ trans('messages.open_log') }}
						</a>
					</li>
					<li rel0="CampaignController/clickLog" class="dropdown">
						<a href="{{ action('CampaignController@clickLog', $campaign->uid) }}">
							<span class="material-icons-outlined">
auto_awesome
</span> {{ trans('messages.click_log') }}
						</a>
					</li>
					<li rel0="CampaignController/unsubscribeLog" class="dropdown">
						<a href="{{ action('CampaignController@unsubscribeLog', $campaign->uid) }}">
							<span class="material-icons-outlined">
auto_awesome
</span> {{ trans('messages.unsubscribe_log') }}
						</a>
					</li>
				</ul>
			</li>
			<li rel0="CampaignController/templateReview" class="dropdown">
				<a href="{{ action('CampaignController@templateReview', $campaign->uid) }}" class="level-1">
					<span class="material-icons-outlined">
auto_awesome_mosaic
</span> {{ trans('messages.email_review') }}
				</a>
			</li>
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
