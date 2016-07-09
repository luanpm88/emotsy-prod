                <div class="row">
                    <div class="col-md-6">
                        <div class="content-group-sm">
                            <div class="pull-right progress-right-info text-teal-800">15%</div>
                            <h5 class="text-semibold">{{ trans('messages.average_open_rate') }}</h5>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-teal" style="width: 15%">
                                    <span class="sr-only">15% Complete</span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="content-group-sm">
                            <div class="pull-right progress-right-info text-teal-800">15%</div>
                            <h5 class="text-semibold">{{ trans('messages.average_click_rate') }}</h5>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-teal" style="width: 15%">
                                    <span class="sr-only">15% Complete</span>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-white bg-teal-400">
                            <div class="panel-body text-center">
                                <h2 class="text-semibold mb-10 mt-0">{{ $list->subscribeRate() }}%</h2>
                                <div class="text-muted">{{ trans('messages.avg_subscribe_rate') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-white bg-teal-400">
                            <div class="panel-body text-center">
                                <h2 class="text-semibold mb-10 mt-0">{{ $list->unsubscribeRate() }}%</h2>
                                <div class="text-muted">{{ trans('messages.avg_unsubscribe_rate') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-white bg-teal-400">
                            <div class="panel-body text-center">
                                <h2 class="text-semibold mb-10 mt-0">{{ $list->unsubscribeCount() }}</h2>
                                <div class="text-muted">{{ trans('messages.total_unsubscribers') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-white bg-teal-400">
                            <div class="panel-body text-center">
                                <h2 class="text-semibold mb-10 mt-0">{{ $list->unconfirmedCount() }}</h2>
                                <div class="text-muted">{{ trans('messages.total_unconfirmed') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />