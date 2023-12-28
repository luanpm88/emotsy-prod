<div>
    @if (!$campaign)
        <div class="empty-list">
            <span class="material-symbols-rounded">auto_awesome</span>
            <span class="line-1">
                {{ trans('messages.no_sent_campaigns') }}
            </span>
        </div>
    @else
        <h6 class="mt-5 mb-3 support-request">
            {{ trans('messages.dashboard.campaign_overview') }}
        </h6>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card position-relative h-100 box-card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 pe-4 mb-4">
                                <div class="inquery d-flex flex-column  justify-content-between ">
                                    <!-- Example single danger button -->
                                    <div class="btn-group e-type mb-4">  
                                        <button type="button" class="btn revert btn-outline-emotsy dropdown-toggle" data-bs-toggle="dropdown"  aria-expanded="false">
                                            {{ $campaign->name }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach (Acelle\Model\Campaign::done()->where('id', '!=', $campaign->id)->get() as $c)
                                                <li>
                                                    <a class="dropdown-item" href="{{ action('HomeController@index', [
                                                        'campaign_uid' => $c->uid
                                                    ]) }}">{{ $c->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="mb-4 efrom">
                                        <label class="form-label e-label">{{ trans('messages.from_email') }}</label>
                                        <div class="e-value">{{ $campaign->from_email }}</div>
                                    </div>

                                    <div class="mb-4 esubject">
                                        <label class="form-label e-label">{{ trans('messages.subject') }}</label>
                                        <div class="e-value">
                                            @if ($campaign->isABTest())
                                                <div class="">
                                                    <div class="d-flex mb-1">
                                                        <div class="me-2">
                                                            <span>{{ trans('messages.campaign.ab.version_a') }}:</span>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0">
                                                                {{ $campaign->subject }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex mb-0">
                                                        <div class="me-2">
                                                            <span>{{ trans('messages.campaign.ab.version_b') }}:</span>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0">
                                                                {{ $campaign->campaignB->subject }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span>{{ $campaign->subject }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="e-date mb-4">
                                        <div class="e-col">
                                            <div class="mb-4 efrom">
                                                <label class="form-label e-label">{{ trans('messages.run_at') }}:</label>
                                                <div class="e-value">{{ isset($campaign->run_at) ? Auth::user()->customer->formatDateTime($campaign->run_at, 'datetime_full') : "" }}</div>
                                            </div>
                                        </div>
                                        <div class="e-col">
                                            <div class="mb-4 efrom">
                                                <label class="form-label e-label">{{ trans('messages.delivery_at') }}:</label>
                                                <div class="e-value">{{ isset($campaign->delivery_at) ? Auth::user()->customer->formatDateTime($campaign->delivery_at, 'datetime_full') : "" }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex e-listing">
                                        <h2 class="text-semibold rate-value rate-color">{{ ($campaign->deliveredCount()) }}</h2>
                                        <div class="text-muteds pl-2 rate-alias pt-1">
                                            <div class="alias-items">{{ trans('messages.successful_deliveries') }}</div>
                                            <div class="alias-value pt-1">{{ ($campaign->deliveredCount()) }}/{{ ($campaign->readCache('SubscriberCount', 0)) }}</div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-md-5 px-2 mb-4">
                                <div class="row">
                                    <div class="col col-sm-4 mb-4">
                                        <div class="chart-title">{{ trans('messages.opened') }}</div>
                                        <div id="chart1" class="chart">
                                            <div class="c100 p{{ round($campaign->readCache('UniqOpenRate')*100, 0) }}">
                                                <span>{{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chart-alias" style="text-transform:lowercase;white-space:nowrap;">
                                            {{ $campaign->readCache('UniqOpenCount') }} {{ trans('messages.dashboard.unique') }} / {{ $campaign->uniqueOpenCount() }} {{ trans('messages.opens') }} 
                                            <br>
                                            {{ ($campaign->deliveredCount()) }} {{ trans('messages.dashboard.messages') }}
                                        </div>
                                    </div>
                                    <div class="col col-sm-4 mb-4">
                                        <div class="chart-title">{{ trans('messages.not_opened') }}</div>
                                        <div id="chart2" class="chart">
                                            <div class="c100 p{{ round($campaign->readCache('NotOpenRate')*100) }}">
                                                <span>{{ number_to_percentage($campaign->readCache('NotOpenRate')) }}</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chart-alias" style="text-transform:lowercase;white-space:nowrap;">
                                            {{ $campaign->readCache('NotOpenCount') }} {{ trans('messages.clicks') }} /<br>
                                            {{ ($campaign->deliveredCount()) }} {{ trans('messages.dashboard.messages') }}
                                        </div>
                                    </div>

                                    <div class="col col-sm-4 mb-4">
                                        <div class="chart-title">{{ trans('messages.clicked_emails_rate') }}</div>
                                        <div id="chart3" class="chart">
                                            <div class="c100 p{{ round($campaign->readCache('ClickedRate')*100, 0) }}">
                                                <span>{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="chart-alias" style="text-transform:lowercase;white-space:nowrap;">
                                            {{ $campaign->uniqueClickCount() }} {{ trans('messages.dashboard.unique') }} / {{ $campaign->openUniqCount() }} {{ trans('messages.opens') }} 
                                            <br>
                                            {{ ($campaign->deliveredCount()) }} {{ trans('messages.dashboard.messages') }}
                                        </div>
                                    </div>
                                    <div class="col col-sm-4 mb-4">
                                        <div class="chart-title">{{ trans('messages.unsubscribed') }}</div>
                                        <div id="chart4" class="chart">
                                            <div class="c100 p{{ round($campaign->unsubscribeRate()*100, 0) }}">
                                                <span>{{ number_to_percentage($campaign->unsubscribeRate()) }}</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="chart-alias" style="text-transform:lowercase;white-space:nowrap;">
                                            {{ trans('messages.count_unsubscribed', [
                                                'count' => $campaign->unsubscribeCount()
                                            ]) }}
                                        </div>
                                    </div>

                                    <div class="col col-sm-4 mb-4">
                                        <div class="chart-title"> {{ trans('messages.bounced') }}</div>
                                        <div id="chart5" class="chart">
                                            <div class="c100 p{{ round($campaign->bounceRate()*100, 0) }}">
                                                <span>{{ number_to_percentage($campaign->bounceRate()) }}</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="chart-alias" style="text-transform:lowercase;white-space:nowrap;">
                                            {{ trans('messages.count_bounced', [
                                                'count' => $campaign->bounceCount()
                                            ]) }}
                                        </div>
                                    </div>
                                    <div class="col col-sm-4 mb-4">
                                        <div class="chart-title">{{ trans('messages.reported') }}</div>
                                        <div id="chart6" class="chart">
                                            <div class="c100 p{{ round($campaign->feedbackRate()*100, 0) }}">
                                                <span>{{ number_to_percentage($campaign->feedbackRate()) }}</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="chart-alias" style="text-transform:lowercase;white-space:nowrap;">
                                            {{ trans('messages.count_reported', [
                                                'count' => $campaign->feedbackCount()
                                            ]) }}
                                        </div>
                                    </div> 

                                </div>
                                <div class="l" id="chart"></div>
                            </div>
                            <div class="col-md-3 ps-4"> 
                                
                                <div class="content-group-sm mb-4 w-100 light-blue">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.recipients') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar  bg-primary" style="width:100%">
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="content-group-sm mb-4 w-100 blue">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.delivered') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar  bg-primary" style="width: {{ !$campaign->readCache('SubscriberCount', 0) ? '--' : ($campaign->readCache('DeliveredCount', 0)/$campaign->readCache('SubscriberCount', 0))*100 }}%">
                                            </div>
                                        </div> 
                                    </div>

                                </div>
                                <div class="content-group-sm mb-4 w-100 orange">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.failed') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar  bg-primary" style="width: {{ !$campaign->readCache('SubscriberCount', 0) ? '--' : ($campaign->readCache('FailedDeliveredCount', 0)/$campaign->readCache('SubscriberCount', 0))*100 }}%">
                                            </div>
                                        </div> 
                                    </div>

                                </div>
                                <div class="content-group-sm mb-4 w-100 green">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.open') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar  bg-primary" style="width: {{ !$campaign->readCache('SubscriberCount', 0) ? '--' : ($campaign->readCache('UniqOpenCount', 0)/$campaign->readCache('SubscriberCount', 0))*100 }}%">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="content-group-sm mb-4 w-100 yellow">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.clicked') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: {{ !$campaign->readCache('SubscriberCount', 0) ? '--' : ($campaign->uniqueClickCount()/$campaign->readCache('SubscriberCount', 0))*100 }}%">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                

                                <div class="content-group-sm mb-4 w-100 silver">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.bounce') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: {{ !$campaign->readCache('SubscriberCount', 0) ? '--' : ($campaign->bounceCount()/$campaign->readCache('SubscriberCount', 0))*100 }}%">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="content-group-sm mb-4 w-100 silver">
                                    <div class="something">
                                        <div class="d-flex">
                                            <div class="progress-items">{{ trans('messages.report') }}</div> 
                                        </div>                                               
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar  bg-primary" style="width: {{ !$campaign->readCache('SubscriberCount', 0) ? '--' : ($campaign->feedbackCount()/$campaign->readCache('SubscriberCount', 0))*100 }}%">
                                            </div>
                                        </div> 
                                    </div>
                                </div>


                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 pb-4">
                <div class="card position-relative h-100 box-card rate-blue">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between p-4 w-100">
                            <h2>{{ trans('messages.open_rate') }}</h2>
                            <a href="{{ action('CampaignController@openLog', [
                                'uid' => $campaign->uid,
                                'version_b' => request()->version_b,
                            ]) }}">{{ trans('messages.open_log') }}</a>
                        </div> 
                    </div>
                    <div class="card-body p-4 d-flex align-items-start">
                        <div class="d-flex justify-content-between w-100 pt-3"> 
                            <div class="frog w-60"> 
                                <div class="col-md-12">
                                    <div class="content-group-sm"> 
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="progress progress-sm h-10 w-100">
                                                <div class="progress-bar bg-primary" style="width: {{ $campaign->readCache('UniqOpenRate')*100 }}%">
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="d-flex ">
                                            <h2 class="text-semibold rate-value rate-color">{{ number_with_delimiter($campaign->readCache('DeliveredCount')) }}</h2>
                                            <div class="text-muteds pl-2 rate-alias pt-1">
                                                <div class="alias-items">{{ trans('messages.successful_deliveries') }}</div>
                                                <div class="alias-value pt-1">{{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="frog w-40 d-flex flex-column">
                                <div class="rate-last-title mb-4" style="line-height:14px">
                                    {{ trans('messages.last_opened') }}
                                    <span class="rate-last-subtitle pl-2">{{ $campaign->lastOpen() ? Auth::user()->customer->formatDateTime($campaign->lastOpen()->created_at, 'datetime_full') : "" }}</span>
                                </div>
                                <div class="d-flex">
                                    <div class="rate-open w-50">
                                        <div class="rate-open-result fs-36">{{ number_with_delimiter($campaign->readCache('UniqOpenCount')) }}</div>
                                        <p>{{ trans('messages.uniq_opens') }}</p>
                                    </div>
                                    <div class="rate-open w-50">
                                        <div class="rate-open-result fs-36">{{ number_with_delimiter($campaign->uniqueOpenCount()) }}</div>
                                        <p>{{ trans('messages.total_opens') }}</p>
                                    </div>
                                </div> 
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pb-4">
                <div class="card position-relative h-100 box-card rate-orange">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between p-4 w-100">
                            <h2>{{ trans('messages.click_rate') }}</h2>
                            <a href="{{ action('CampaignController@clickLog', [
                                'uid' => $campaign->uid,
                                'version_b' => request()->version_b,
                            ]) }}">{{ trans('messages.click_log') }}</a>
                        </div> 
                    </div>
                    <div class="card-body py-4 px-3 d-flex align-items-start">
                        <div class="d-flex justify-content-between w-100 pt-3"> 
                            <div class="frog w-60"> 
                                <div class="col-md-12">
                                    <div class="content-group-sm"> 
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="progress progress-sm h-10 w-100">
                                                <div class="progress-bar bg-primary" style="width: {{ $campaign->readCache('ClickedRate')*100 }}%">
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="d-flex ">
                                            <h2 class="text-semibold rate-value rate-color">{{ number_with_delimiter($campaign->uniqueClickCount()) }}</h2>
                                            <div class="text-muteds pl-2 rate-alias pt-1">
                                                <div class="alias-items">{{ trans('messages.total_clicks') }}</div>
                                                <div class="alias-value pt-1">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="frog w-40 d-flex flex-column">
                                <div class="rate-last-title mb-4" style="line-height:14px">
                                    {{ trans('messages.last_clicked') }}
                                    <span class="rate-last-subtitle pl-2">{{ $campaign->lastClick() ? Auth::user()->customer->formatDateTime($campaign->lastClick()->created_at, 'datetime_full') : "" }}</span>
                                </div>
                                <div class="d-flex">
                                    <div class="rate-open w-50">
                                        <div class="rate-open-result fs-36">{{ number_with_delimiter($campaign->uniqueClickCount()) }}</div>
                                        <p>{{ trans('messages.total_clicks') }}</p>
                                    </div>
                                    <div class="rate-open w-50">
                                        <div class="rate-open-result fs-36">{{ number_with_delimiter($campaign->uniqueOpenCount()) }}</div>
                                        <p>{{ trans('messages.total_opens') }}</p>
                                    </div>
                                </div> 
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    
</div>