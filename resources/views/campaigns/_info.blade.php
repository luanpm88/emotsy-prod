<h2 class="mt-4 mb-4">
    <span class="text-teal text-bold">{{ $campaign->readCache('SubscriberCount', 0) }}</span>
    {{ trans('messages.' . \Acelle\Library\Tool::getPluralPrase('recipient', $campaign->readCache('SubscriberCount', 0))) }}
</h2>

<div class="row fs-6">
    <div class="col-md-6 campaigns-summary">
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.from') }}:</span>
            @if (is_object($campaign->defaultMailList))
                <a href="{{ action('MailListController@overview', ['uid' => $campaign->defaultMailList->uid]) }}">
                    {!! $campaign->displayRecipients() !!}
                </a>
            @else
                {!! $campaign->displayRecipients() !!}
            @endif
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.subject') }}:</span>
            {{ $campaign->subject }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.from_email') }}:</span>
            {{ $campaign->from_email }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.from_name') }}:</span>
            {{ $campaign->from_name }}
        </div>

    </div>
    <div class="col-md-6">
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.reply_to') }}:</span>
            {{ $campaign->reply_to }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.updated_at') }}:</span>
            {{ Tool::formatDateTime($campaign->updated_at) }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.run_at') }}:</span>
            {{ isset($campaign->run_at) ? Tool::formatDateTime($campaign->run_at) : "" }}
        </div>
        <div class="mb-2">
            <span class="text-bold d-inline-block" style="width:120px">{{ trans('messages.delivery_at') }}:</span>
            {{ isset($campaign->delivery_at) ? Tool::formatDateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $campaign->delivery_at)) : "" }}
        </div>
    </div>
</div>
