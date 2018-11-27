<form action="{{ action('AccountSubscriptionController@subscribe', [
    'gateway' => $gateway['name'],
    'plan_uid' => $plan->uid,
    '_token' => csrf_token(),
]) }}" method="POST">
    <script
      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
      data-key="{{ $gateway['fields']['publishable_key'] }}"
      data-amount="{{ $plan->stripePrice() }}"
      data-currency="{{ $plan->currency->code }}"
      data-email="{{ Auth::user()->email }}"
      data-name="{{ \Acelle\Model\Setting::get('site_name') }}"
      data-description="{{ \Acelle\Model\Setting::get('site_description') }}"
      data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
      data-locale="{{ language_code() }}"
      data-zip-code="true"
      data-label="{{ trans('messages.pay_with_strip_label_button') }}">
    </script>
</form>