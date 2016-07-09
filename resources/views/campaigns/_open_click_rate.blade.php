            <div class="row">
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <div class="pull-right progress-right-info text-teal-800">{{ $campaign->openRate() }}%</div>
                        <h4 class="text-semibold">{{ trans('messages.open_rate') }}</h4>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-teal" style="width: {{ $campaign->openRate() }}%">
                            </div>
                        </div>
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ $campaign->trackingCount() }}
                                <span class="text-muted2">{{ $campaign->deliveredRate() }}%</span>
                            </div>
                            <p class="text-muted">{{ trans('messages.successful_deliveries') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ $campaign->openCount() }}
                            </div>
                            <p class="text-muted">{{ trans('messages.total_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ $campaign->openUniqCount() }}
                            </div>
                            <p class="text-muted">{{ trans('messages.uniq_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ Mailixa\Library\Tool::formatDateTime($campaign->lastOpen()->created_at) }}
                            </div>
                            <p class="text-muted">{{ trans('messages.last_opened') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <div class="pull-right progress-right-info text-teal-800">{{ $campaign->clickPerUniqOpen() }}%</div>
                        <h4 class="text-semibold">{{ trans('messages.click_rate') }}</h4>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-teal" style="width: {{ $campaign->clickPerUniqOpen() }}%">
                            </div>
                        </div>
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ $campaign->clickPerUniqOpen() }}%
                            </div>
                            <p class="text-muted">{{ trans('messages.clicks_per_unique_open') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ $campaign->clickCount() }}
                            </div>
                            <p class="text-muted">{{ trans('messages.total_clicks') }}</p>
                        </div>                        
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ $campaign->abuseFeedbackCount() }}
                            </div>
                            <p class="text-muted">{{ trans('messages.abuse_reports') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                {{ Mailixa\Library\Tool::formatDateTime($campaign->lastClick()->created_at) }}
                            </div>
                            <p class="text-muted">{{ trans('messages.last_clicked') }}</p>
                        </div>
                    </div>
                </div>
            </div>