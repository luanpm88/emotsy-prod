<h3 class="mt-10"><i class="icon-stats-dots"></i> {{ trans('messages.statistics') }}</h3>

<div class="panel panel-flat">
    <div class="panel-body">
        <div class="chart-container">
            <div class="chart has-fixed-height" id="d3-bar-horizontal"  data-url="{{ action('CampaignController@chart', $campaign->uid) }}"></div>
        </div>
    </div>
</div>