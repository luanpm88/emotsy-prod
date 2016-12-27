<li class="child-row">
    <a href="{{ action('CampaignController@overview', $campaign->uid) }}" data-popup="tooltip" title="{{ trans('messages.overview') }}" type="button" class="btn bg-teal-600 btn-icon">{{ trans('messages.overview') }}</a>
    
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