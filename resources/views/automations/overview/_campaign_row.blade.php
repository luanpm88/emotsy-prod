<li class="child-row">
    <h5 class="mb-5 text-semibold"><i class="icon-envelop"></i> {{ $campaign->name }}</h5>
    @if ($campaign->autoCampaignDesigned())
        <p>
            {{ trans('messages.updated_at') }}: {{ Tool::formatDateTime($campaign->updated_at) }}
        </p>
    @else
        <p class="text-danger">
            {{ trans('messages.not_designed_yet') }}
        </p>
    @endif
</li>