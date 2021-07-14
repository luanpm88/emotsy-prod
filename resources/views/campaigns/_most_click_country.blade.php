<h3 class="mt-5 mb-3"><span class="material-icons-round me-2">
    ads_click
    </span> {{ trans('messages.top_country_by_clicks') }}</h3>
<div class="row">
    <div class="col-md-6">
        @if (!$campaign->clickCount())                        
            <div class="empty-chart-pie">
                <div class="empty-list">
                    <span class="material-icons-outlined">
auto_awesome
</span>
                    <span class="line-1">
                        {{ trans('messages.log_empty_line_1') }}
                    </span>
                </div>
            </div>
        @else
            <div class="stat-table">
                @foreach ($campaign->topClickCountries(7)->get() as $location)
                    <div class="stat-row">
                        <p class="text-muted">{{ $location->country_name }}</p>
                        <div class="pull-right num">
                            {{ $location->aggregate }}
                        </div>
                    </div>
                @endforeach 
            </div>
            <div class="text-end">
                <a href="{{ action('CampaignController@clickLog', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.click_log') }} <span class="material-icons-round">
arrow_forward
</span></a>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        @if ($campaign->clickCount())
            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="chart-container has-scroll">
                        <div class="chart has-fixed-height" id="basic_pie_4"  data-url="{{ action('CampaignController@chartClickCountry', $campaign->uid) }}"></div>
                    </div>
                </div>
            </div>
                
        @else
            <div class="empty-chart-pie">
                <div class="empty-list">
                    <span class="material-icons-outlined">
auto_awesome
</span>
                    <span class="line-1">
                        {{ trans('messages.log_empty_line_1') }}
                    </span>
                </div>
            </div>
        @endif
    </div>                
</div>