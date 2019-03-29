<form action="https://www.coinpayments.net/index.php" method="post">
	<input type="hidden" name="cmd" value="_pay_simple">
	<input type="hidden" name="reset" value="1">
	<input type="hidden" name="merchant" value="{{ $gateway['fields']['merchant_id'] }}">
	<input type="hidden" name="currency" value="{{ $plan->currency->code }}">
	<input type="hidden" name="amountf" value="{{ $plan->price }}">
	<input type="hidden" name="item_name" value="{{ trans('messages.invoice.subscribe_to_plan', [
		'plan' => $subscription->plan->name,
	]) }}">
	<input type="hidden" name="item_number" value="{{ $subscription->uid }}">
	<input type="hidden" name="item_desc" value="{{ json_encode([
		'createdAt' => $subscription->created_at->timestamp,
		'periodEndsAt' => $subscription->ends_at->timestamp,
		'amount' => Acelle\Library\Tool::format_price($subscription->plan->price, $subscription->plan->currency->format),
	]) }}">
	<input type="hidden" name="success_url" value="{{ action('AccountSubscriptionController@index') }}">
	<input type="image" src="https://www.coinpayments.net/images/pub/CP-main-large.png" alt="Buy Now with CoinPayments.net">
</form>