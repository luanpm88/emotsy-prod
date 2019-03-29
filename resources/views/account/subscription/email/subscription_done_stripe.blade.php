<p>{!! trans('messages.subscription_done.stripe.email', [
    'customer' => $customerName,
    'plan' => $planName,
    'link' => $link,
]) !!}</p>