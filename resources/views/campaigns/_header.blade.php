<div class="page-title">
	<ul class="breadcrumb breadcrumb-caret position-right">
		<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		@if (!empty($campaign->autoEvents))
			<li><a href="{{ action("AutomationController@index") }}">{{ trans('messages.automations') }}</a></li>
			<li><a href="{{ action("AutomationController@overviewWorkflow", $campaign->autoEvent()->automation->uid) }}">{{ $campaign->autoEvent()->automation->name }}</a></li>
			<li><a href="{{ action("AutomationController@overviewCampaigns", $campaign->autoEvent()->automation->uid) }}">{{ trans('messages.campaigns') }}</a></li>
		@else
			<li><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
		@endif
	</ul>
	<h1>
		<span class="text-semibold">{{ $campaign->name }}</span>
	</h1>
</div>