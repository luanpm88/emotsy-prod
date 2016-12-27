<ul class="nav nav-pills campaign-steps">
    <li class="{{ $step == "workflow" ? "active" : "" }}">
        <a href="{{ action('AutomationController@overviewWorkflow', $automation->uid) }}">
            <i class="icon-checkmark4"></i> {{ trans('messages.workflow') }}
        </a>
    </li>
    <li class="{{ $step == "campaigns" ? "active" : "" }}">
		<a href="{{ action('AutomationController@overviewCampaigns', $automation->uid) }}">
			<i class="icon-envelop"></i> {{ trans('messages.emails') }}
		</a>
	</li>
</ul>