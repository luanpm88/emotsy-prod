<form action="https://www.coinpayments.net/index.php" method="post">
	<input type="hidden" name="cmd" value="_pay_simple">
	<input type="hidden" name="reset" value="1">
	<input type="hidden" name="merchant" value="{{ $gateway['fields']['merchant_id'] }}">
	<input type="hidden" name="currency" value="BTC">
	<input type="hidden" name="amountf" value="1.0">
	<input type="hidden" name="item_name" value="{{ $plan->name }}">	
	<input type="image" src="https://www.coinpayments.net/images/pub/buynow-grey.png" alt="Buy Now with CoinPayments.net">
</form>