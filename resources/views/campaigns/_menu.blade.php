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
		</ul>
	</div>
</div>