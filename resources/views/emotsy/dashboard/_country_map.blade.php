<div class="card position-relative h-100 box-card"> 
    <div class="card-body p-4">
        <div class="p-4 w-100">
            <h2>Contries</h2> 
        </div>
        <div class="p-4 w-100">
            @if (!$campaign->uniqueOpenCount())
                <div class="empty-chart-pie">
                    <div class="empty-list">
                        <span class="material-symbols-rounded">auto_awesome</span>
                        <span class="line-1">
                            {{ trans('messages.log_empty_line_1') }}
                        </span>
                    </div>
                </div>
            @else
                <div class="stat-table">
                    @foreach ($campaign->topOpenCountries(7)->get() as $location)
                        <div class="stat-row">
                            <p class="">
                                <img class="flag-icon" src="{{ url($location->getFlagPath()) }}" />
                                {{ $location->getCountryName() }}
                            </p>
                            <span class="pull-right num">
                                {{ number_with_delimiter($location->aggregate, $precision = 0) }}
                            </span>
                        </div>
                    @endforeach 
                </div>
                <div class="text-end">
                    <a href="{{ action('CampaignController@openMap', [
                        'uid' => $campaign->uid,
                        'version_b' => request()->version_b,
                    ]) }}" class="btn btn-info bg-teal-600">{{ trans('messages.open_map') }} <span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
            @endif
        </div>
    </div>
</div>