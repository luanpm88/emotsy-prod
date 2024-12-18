<div data-control="tier-info">
    <div class="mt-3 mb-5">
        <div class="d-flex align-items-center">
            <div class="price me-auto">
                {!! format_price($plan->price, $plan->currency->format, true) !!}
                <span class="p-currency-code">{{ $plan->currency->code }}</span>
            </div>
            <p class="mt-2 mb-0 fw-semibold"><span class="material-symbols-rounded me-2" style="color: #00A3B9;">restore</span> {{ $plan->displayFrequencyTime() }}</p>
        </div>
    </div>
    <hr class="mb-2 border-0" style="width: 40px">
    <div style="height: 40px" class="mb-4">
        <label class="plan-title fs-5 fw-600 mt-0">{{ $plan->name }}</label>
    </div>

    <span class="time-box d-block text-start small py-4 px-4 fw-600 mb-5 plan-tier-info-box">
        <div class="mb-2">
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
                    class="btn fw-600 btn-primary d-block py-2 shadow-sm buy-button"
                    style="
                        
                    "
                >
                        @if ($plan->isFree() || $plan->hasTrial())
                            {{ trans('messages.plan.select') }}
                        @else
                            {{ trans('messages.plan.buy') }}
                        @endif
                </a>
            @endif
        </div>
    </div>
</div>

<div data-control="tier-details">
    {{-- <hr> --}}
    @include('plans._details', [
        'plan' => $plan,
    ])
</div>