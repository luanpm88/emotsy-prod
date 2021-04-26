<div class="mt-2">
    @if (isset($endsAt))
        <div>
            {{ trans('messages.ends_on') }}:
            <strong>{{ \Acelle\Library\Tool::formatDate($endsAt) }}</strong>
        </div>
        <hr class="my-3">
    @endif
    <div>
        {{ trans('messages.sending_total_quota_label') }}: {{ $plan->displayTotalQuota() }}
    </div>
    <div>
        {{ trans('messages.max_lists_label') }}: {{ $plan->displayMaxList() }}
    </div>
    
</div>