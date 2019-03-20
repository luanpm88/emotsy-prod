<h3 class="text-semibold">{{ trans('messages.subscription.checkout') }}</h3>
<p>
    {!! trans('messages.subscription.pending.direct.wording', [
        'plan' => $subscription->plan->name,
        'money' => Acelle\Library\Tool::format_price($subscription->plan->price, $subscription->plan->currency->format),
    ]) !!}
</p>
<a link-method="POST"
    class="btn btn-mc_primary"
    href="{{ action('AccountSubscriptionController@subscribe', ['plan_uid' => $subscription->plan->uid]) }}">
    {{ trans('messages.subscription.payment.checkout_again') }}
</a>