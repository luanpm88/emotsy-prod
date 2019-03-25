<h3 class="text-semibold">{{ trans('messages.subscription.checkout') }}</h3>
<p>
    {!! trans('messages.subscription.pending.coinpayments.wording', [
        'plan' => $subscription->plan->name,
        'money' => Acelle\Library\Tool::format_price($subscription->plan->price, $subscription->plan->currency->format),
    ]) !!}
</p>
        
<a link-method="POST"
    class="btn btn-mc_primary mr-10"
    href="{{ action('AccountSubscriptionController@subscribe', ['plan_uid' => $subscription->plan->uid]) }}">
    {{ trans('messages.subscription.payment.checkout_again') }}
</a>
@if (\Auth::user()->customer->can('cancelNow', $subscription))
    <a link-method="POST" link-confirm="{{ trans('messages.subscription.cancel_now.confirm') }}"
        href="{{ action('AccountSubscriptionController@cancelNow') }}"
        class="btn bg-grey-600 mr-10"
    >
        {{ trans('messages.subscription.cancel_now') }}
    </a>
@endif