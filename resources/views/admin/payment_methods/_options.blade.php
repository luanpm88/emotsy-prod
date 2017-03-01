@if ($payment_method->type == \Acelle\Model\PaymentMethod::TYPE_PAYPAL)
    <div class="row">
        <div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[merchant_id]',
                'label' => trans('messages.paypal_merchant_id'),
				'value' => $payment_method->getOption('merchant_id'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
    </div>
@endif