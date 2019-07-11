<div class="row">
	<div class="col-md-6">
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[publishable_key]',
            'value' => $gateway['fields']['publishable_key'],
            'label' => trans('messages.payment.stripe.publishable_key'),
            'help_class' => 'payment',
            'rules' => ['options.publishable_key' => 'required'],
        ])
        
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'options[secret_key]',
            'value' => $gateway['fields']['secret_key'],
            'label' => trans('messages.payment.stripe.secret_key'),
            'help_class' => 'payment',
            'rules' => ['options.secret_key' => 'required'],
        ])
    </div>
</div>