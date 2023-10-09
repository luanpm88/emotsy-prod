<li class="d-flex">
    <span class="fs-4 me-4">
        <i class="material-symbols-rounded text-success">task_alt</i>
    </span>
    <div class="me-auto" style="width:100%">
        <div class="">
            <div class="d-flex align-items-center">
                <h4 class="me-auto">{{ trans('messages.campaign.ab.split_options') }}</h4>
                <div>
                    <a href="{{ action('CampaignController@template', $campaign->uid) }}" class="btn btn-default">{{ trans('messages.campaign.back_to_step') }}</a>
                </div>
            </div>
        </div>
        <div class="mt-3 border p-3 bg-light">
            <div class="">
                <div class="d-flex mb-2">
                    <div class="me-2">
                        <strong>{{ trans('messages.campaign.ab.test_group_size') }}:</strong>
                    </div>
                    <div>
                        <p>
                            {{ trans('messages.campaign.ab.version_a') }}:
                            <strong>{{ $campaign->getABpercent() }}%</strong>
                            (~<span split-control="aprox-b">{{ number_format(round($campaign->subscribersCount()*($campaign->getABpercent()/100))) }}</span> {{ trans('messages.recipients') }})
                        </p>
                        <p>
                            {{ trans('messages.campaign.ab.version_b') }}:
                            <strong>{{ $campaign->getABpercent() }}%</strong>
                            (~<span split-control="aprox-b">{{ number_format(round($campaign->subscribersCount()*($campaign->getABpercent()/100))) }}</span> {{ trans('messages.recipients') }})
                        </p>
                        <p>
                            {{ trans('messages.campaign.ab.version_winning') }}:
                            <strong>{{ $campaign->ab_winning_percent }}%</strong>
                            (~<span split-control="aprox-b">{{ number_format(round($campaign->subscribersCount()*($campaign->ab_winning_percent/100))) }}</span> {{ trans('messages.recipients') }})
                        </p>
                    </div>
                </div>
                <div class="d-flex mb-2">
                    <div class="me-2">
                        <strong>{{ trans('messages.campaign.ab.choose_winning_version') }}:</strong>
                    </div>
                    <div>
                        <p>
                            @if ($campaign->ab_winning_metric == 'click')
                                {{ trans('messages.clicks') }}
                            @else
                                {{ trans('messages.opens') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="d-flex mb-2">
                    <div class="me-2">
                        <strong>{{ trans('messages.campaign.ab.duration_test') }}:</strong>
                    </div>
                    <div>
                        <p>
                            {{ trans_choice('messages.interval.' . $campaign->ab_test_duration_unit, $campaign->ab_test_duration_value) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>