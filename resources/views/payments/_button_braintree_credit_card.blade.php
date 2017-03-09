<button type='submit'
    class="btn bg-teal payment_method_type_button"
    data-value="{{ $payment_method->uid }}">
    {!! trans('messages.payment_method_type_braintree_credit_card_button') !!}
</button>