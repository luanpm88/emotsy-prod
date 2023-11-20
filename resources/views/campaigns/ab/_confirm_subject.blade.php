<li class="d-flex">
    <span class="fs-4 me-4">
        <i class="material-symbols-rounded text-success">task_alt</i>
    </span>
    <div class="me-auto" style="width:100%">
        <div class="">
            <div class="d-flex align-items-center">
                <h4 class="me-auto">{{ trans('messages.email_subject') }}</h4>
                <div>
                    <a href="{{ action('CampaignController@recipients', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" class="btn btn-default">{{ trans('messages.campaign.back_to_step') }}</a>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="border px-4 py-3 rounded bg-light" style="width:100%">
                <div class="d-flex mb-1">
                    <div class="me-3">
                        <strong>{{ trans('messages.campaign.ab.version_a') }}:</strong>
                    </div>
                    <div>
                        <p>
                            {{ $campaign->subject }}
                        </p>
                    </div>
                </div>
                <div class="d-flex mb-0">
                    <div class="me-3">
                        <strong>{{ trans('messages.campaign.ab.version_b') }}:</strong>
                    </div>
                    <div>
                        <p>
                            {{ $campaign->campaignB->subject }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>