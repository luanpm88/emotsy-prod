<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="frmTransaction" id="frmTransaction">
    <input type="hidden" name="business" value="{{ $subscription->paymentMethod->getOption('merchant_id') }}"> 
    <input type="hidden" name="cmd" value="_xclick"> 
    <input type="hidden" name="item_name" value="{{ $subscription->getName() }}"> 
    <input type="hidden" name="item_number" value="{{ $subscription->getID() }}">
    <input type="hidden" name="amount" value="{{ $subscription->price }}">   
    <input type="hidden" name="currency_code" value="{{ $subscription->currency_code }}">   
    <input type="hidden" name="cancel_return" value="{{ action('PaymentController@paymentCancel', $subscription->uid) }}"> 
    <input type="hidden" name="return" value="{{ action('PaymentController@paymentStatus', $subscription->uid) }}">
</form>
<SCRIPT>document.frmTransaction.submit();</SCRIPT> 