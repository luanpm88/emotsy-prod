@if ($campaign->ab_split_content)
    <li class="d-flex">
        <span class="fs-4 me-4">
            @if ($campaign->template && $campaign->template_b)
                <i class="material-symbols-rounded text-success">task_alt</i>
            @else
                <span class="material-symbols-rounded text-warning">error_outline</span>
            @endif
        </span>
        <div class="me-auto" style="width:100%">
            <div class="">
                <div class="d-flex align-items-center">
                    <h4 class="m-0">{{ trans('messages.template') }}</h4>
                    <div class="ms-auto">
                        <a href="{{ action('CampaignController@template', $campaign->uid) }}" class="btn btn-default">{{ trans('messages.campaign.back_to_step') }}</a>
                    </div>
                </div>
            </div>
            <div class="mt-3 border p-3 bg-light">
                <div class="d-flex align-items-top">
                    <div>
                        <a 
                            onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                            href="javascript:;"
                            data-action="template-variation-box" for="template_variation_a" class="d-inline-block template-variation border p-3 rounded me-4 text-dark bg-white">
                            <div class="mb-2">
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
                    </div>
                    <div>
                        <a 
                            @if (!$campaign->template_b)
                                href="{{ action('CampaignController@template', [
                                    'uid' => $campaign->uid,
                                    'version_b' => true,
                                ]) }}"
                            @else
                                onclick="popupwindow('{{ action('CampaignController@preview', $campaign->uid) }}', `{{ $campaign->name }}`, 800)"
                                href="javascript:;"
                            @endif
                            data-action="template-variation-box" for="template_variation_b" class="d-inline-block template-variation border p-3 rounded text-dark bg-white">
                            <div class="mb-2">
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
                        @if (!$campaign->template_b)
                            <span class="text-danger mt-2 d-block text-center">
                                <span class="material-symbols-rounded text-warning me-1">error_outline</span>
                                {{ trans('messages.campaign.ab.template.not_setup_yet') }}
                            </span>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </li>
@endif