<div class="row">
	<div class="col-md-6">
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[merchant_id]',
            'value' => $gateway['fields']['merchant_id'],
            'label' => trans('messages.payment.coinpayments.merchant_id'),
            'help_class' => 'payment',
            'rules' => ['options.merchant_id' => 'required'],
        ])
        
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[public_key]',
            'value' => $gateway['fields']['public_key'],
            'label' => trans('messages.payment.coinpayments.public_key'),
            'help_class' => 'payment',
            'rules' => ['options.public_key' => 'required'],
        ])
        
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[private_key]',
            'value' => $gateway['fields']['private_key'],
            'label' => trans('messages.payment.coinpayments.private_key'),
            'help_class' => 'payment',
            'rules' => ['options.private_key' => 'required'],
        ])
        
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[ipn_secret]',
            'value' => $gateway['fields']['ipn_secret'],
            'label' => trans('messages.payment.coinpayments.ipn_secret'),
            'help_class' => 'payment',
            'rules' => ['options.ipn_secret' => 'required'],
        ])
		
		@include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[receive_currency]',
            'value' => $gateway['fields']['receive_currency'],
            'label' => trans('messages.payment.coinpayments.receive_currency'),
            'help_class' => 'payment',
            'rules' => ['options.receive_currency' => 'required'],
        ])
    </div>
</div>