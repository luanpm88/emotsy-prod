<div class="row mt-5">
    <div class="col-md-8">
        <div class="mb-4">{{ trans('messages.campaign_open_click_rate_intro') }}</div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="content-group-sm">
            <div class="d-flex">
                <h4 class="text-semibold mt-0 me-auto">{{ trans('messages.open_rate') }}</h4>
                <div class="pull-right progress-right-info text-teal-800">{{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}</div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-color3" style="width: {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}">
                </div>
            </div>
        </div>
        <div class="stat-table">
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.successful_deliveries') }}</p>
                <div class="pull-right num">
                    {{ number_with_delimiter($campaign->readCache('DeliveredCount')) }}
                    <span class="text-muted2">{{ number_to_percentage($campaign->readCache('DeliveredRate')) }}</span>
                </div>
            </div>
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.total_opens') }}</p>
                <div class="pull-right num">
                    {{ number_with_delimiter($campaign->openCount()) }}
                </div>
            </div>
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.uniq_opens') }}</p>
                <div class="pull-right num">
                    {{ number_with_delimiter($campaign->readCache('UniqOpenCount')) }}
                </div>
            </div>
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.last_opened') }}</p>
                <div class="pull-right num">
                    {{ is_object($campaign->lastOpen()) ? Acelle\Library\Tool::formatDateTime($campaign->lastOpen()->created_at) : "" }}
                </div>
            </div>
        </div>
        <div class="text-end">
            <a href="{{ action('CampaignController@openLog', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.open_log') }} <span class="material-icons-round">
arrow_forward
</span></a>
        </div>
        <br />
    </div>
    <div class="col-md-6">
        <div class="content-group-sm">
            <div class="d-flex">
                <h4 class="text-semibold mt-0 me-auto">{{ trans('messages.click_rate') }}</h4>
                <div class="progress-right-info text-teal-800">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-color7" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
                </div>
            </div>
        </div>
        <div class="stat-table">
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.total_clicks') }}</p>
                <div class="pull-right num">
                    {{ number_with_delimiter($campaign->clickCount()) }}
                </div>
            </div>
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.total_opens') }}</p>
                <div class="pull-right num">
                    {{ number_with_delimiter($campaign->openCount()) }}
                </div>
            </div>
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.abuse_reports') }}</p>
                <div class="pull-right num">
                    {{ number_with_delimiter($campaign->abuseFeedbackCount()) }}
                </div>
            </div>
            <div class="stat-row">
                <p class="text-muted">{{ trans('messages.last_clicked') }}</p>
                <div class="pull-right num">
                    {{ is_object($campaign->lastClick()) ? Acelle\Library\Tool::formatDateTime($campaign->lastClick()->created_at) : "" }}
                </div>
            </div>
        </div>
        <div class="text-end">
            <a href="{{ action('CampaignController@clickLog', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.click_log') }} <span class="material-icons-round">
arrow_forward
</span></a>
        </div>
        <br />
    </div>
</div>
