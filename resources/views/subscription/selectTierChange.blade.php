<hr>
<div style="height: 100px">
    <div class="price">
        {!! format_price($plan->price, $plan->currency->format, true) !!}
        <span class="p-currency-code">{{ $plan->currency->code }}</span>
    </div>
    <p><span class="material-symbols-rounded text-muted2">restore</span> {{ $plan->displayFrequencyTime() }}</p>
</div>
<hr class="mb-2" style="width: 40px">
<div style="height: 40px">
    <label class="plan-title fs-5 fw-600 mt-0">{{ $plan->name }}</label>
</div>

<div style="height: 130px">
    <p class="mt-4">{{ $plan->description }}</p>
</div>

<span class="time-box d-block text-center small py-2 fw-600 mb-5">
    <div class="mb-1">
        <span>{{ $plan->displayTotalQuota() }} {{ trans('messages.sending_total_quota_label') }}</span>
    </div>
    <div>
        <span>{{ $plan->displayMaxSubscriber() }} {{ trans('messages.contacts') }}</span>
    </div>
</span>

<div>
    <div style="vertical-align:bottom">
        @if ($subscription->planGeneral->uid == $plan->uid)
            <a
                href="javascript:;"
                class="btn btn-secondary rounded-3 d-block mt-4 shadow-sm pe-none" disabled>
                    {{ trans('messages.plan.current_subscribed') }}
            </a>
        @else
            <a
                link-method="POST"
                href="{{ action('SubscriptionController@changePlan', ['plan_uid' => $plan->uid]) }}"
                class="btn btn-primary rounded-3 d-block mt-4 shadow-sm">
                    {{ trans('messages.plan.select') }}
            </a>
        @endif
    </div>
</div>
{{-- <hr> --}}
@include('plans._details', [
    'plan' => $plan,
])