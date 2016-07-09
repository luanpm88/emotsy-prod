            <div class="row">
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <h4 class="text-semibold"><i class="icon-users4"></i> {{ trans('messages.subscribers_most_open') }}</h4>                        
                    </div>
                    <div class="stat-table">
                        @foreach ($campaign->getTopOpenSubscribers()->get() as $subscriber)
                            <div class="stat-row">
                                <div class="pull-right num">
                                    {{ $subscriber->aggregate }}
                                </div>
                                <p class="text-muted">{{ $subscriber->email }}</p>
                            </div>
                        @endforeach                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <h4 class="text-semibold"><i class="icon-location4"></i> {{ trans('messages.top_location_by_opens') }}</h4>
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.click_per_unique_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.total_clicks') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.last_clicked') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.abuse_reports') }}</p>
                        </div>
                    </div>
                </div>
            </div>