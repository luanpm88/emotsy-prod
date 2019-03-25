<form action="https://www.coinpayments.net/index.php" method="post">
	<input type="hidden" name="cmd" value="_pay_simple">
	<input type="hidden" name="reset" value="1">
	<input type="hidden" name="merchant" value="{{ $gateway['fields']['merchant_id'] }}">
	<input type="hidden" name="currency" value="{{ $subscription->plan->currency->code }}">
	<input type="hidden" name="amountf" value="{{ $subscription->plan->price }}">
	<input type="hidden" name="item_name" value="{{ $subscription->plan->name }}">
	<input type="hidden" name="item_number" value="{{ $subscription->uid }}">
	<input type="hidden" name="success_url" value="{{ action('AccountSubscriptionController@pending') }}">
	<input type="image" src="https://www.coinpayments.net/images/pub/CP-main-large.png" alt="Buy Now with CoinPayments.net">
</form>