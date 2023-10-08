<li class="d-flex">
    <span class="fs-4 me-4">
        @if ($campaign->template)
            <i class="material-symbols-rounded text-success">task_alt</i>
        @else
            <span class="material-symbols-rounded text-warning">error_outline</span>
        @endif
    </span>
    <div class="me-auto">
        <h4>{{ trans('messages.template') }}</h4>
        <div class="mt-2">
            <div class="mb-4">
                <a 
                    onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                    href="javascript:;"
                    data-action="template-variation-box" for="template_variation_a" class="d-inline-block template-variation border p-3 rounded me-4 text-dark">
                    <div class="mb-3">
                        <span class="ms-1 fw-semibold">{{ trans('messages.campaign.ab.version_a') }}</span>
                    </div>
                    <div>
                        <div class="border thumb-container">
                            @if ($campaign->template)
                                <img style="width:100%;" class="template-variation-thumb" src="{{ $campaign->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                            @else
                                <img style="width:100%;" class="template-variation-thumb" src="{{ url('images/icons/template-none.svg') }}" />
                            @endif
                        </div>
                    </div>
                </a>
    
                <a 
                    onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                    href="javascript:;"
                    data-action="template-variation-box" for="template_variation_b" class="d-inline-block template-variation border p-3 rounded text-dark">
                    <div class="mb-3">
                        <span class="ms-1 fw-semibold">{{ trans('messages.campaign.ab.version_b') }}</span>
                    </div>
                    <div>
                        <div class="border thumb-container">
                            @if ($campaign->template_b)
                                <img style="width:100%;" class="template-variation-thumb" src="{{ $campaign->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                            @else
                                <img style="width:100%;" class="template-variation-thumb" src="{{ url('images/icons/template-none.svg') }}" />
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div>
        <a href="{{ action('CampaignController@template', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
    </div>
</li>

<li class="d-flex">
    <span class="fs-4 me-4">
        <i class="material-symbols-rounded text-success">task_alt</i>
    </span>
    <div class="me-auto">
        <h4>{{ trans('messages.campaign.ab.split_options') }}</h4>
        <div class="mt-3">
            <div class="border p-4 rounded">
                <div class="d-flex mb-2">
                    <div class="me-3">
                        <strong>{{ trans('messages.campaign.ab.test_group_size') }}:</strong>
                    </div>
                    <div>
                        <p>
                            {{ trans('messages.campaign.ab.version_a') }}:
                            <strong>{{ $campaign->getABpercent() }}%</strong>
                            (~<span split-control="aprox-b">{{ number_format(round($campaign->subscribersCount()*($campaign->getABpercent()/100))) }}</span> {{ trans('messages.recipients') }})
                        </p>
                        <p>
                            {{ trans('messages.campaign.ab.version_a') }}:
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
                    <div class="me-3">
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
                    <div class="me-3">
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

    <div>
        <a href="{{ action('CampaignController@template', $campaign->uid) }}" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
    </div>
</li>