            <div class="row">
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <h4 class="text-semibold"><i class="icon-location4"></i> {{ trans('messages.top_country_by_opens') }}</h4>
                    </div>
                    <div class="stat-table">
                        @foreach ($campaign->topCountries()->get() as $location)
                            <div class="stat-row">
                                <div class="pull-right num">
                                    {{ $location->aggregate }}
                                </div>
                                <p class="text-muted">{{ $location->country_name }}</p>
                            </div>
                        @endforeach 
                    </div>
                    <div class="text-right">
                        <a href="{{ action('CampaignController@openMap', $campaign->uid) }}" class="btn btn-info bg-teal-600">{{ trans('messages.open_map') }} <i class="icon-arrow-right8"></i></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container has-scroll">
                        <div class="chart has-fixed-height has-minimum-width" id="basic_pie"  data-url="{{ action('CampaignController@chartCountry', $campaign->uid) }}"></div>
                    </div>
                </div>                
            </div>