@if ($payment_method->type == \Acelle\Model\PaymentMethod::TYPE_PAYPAL)
    <div class="row">
        <div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'select',
				'name' => 'options[environment]',
                'label' => trans('messages.environment'),
				'value' => $payment_method->getOption('environment'),
				'options' => [
					['value' => 'sandbox', 'text' => trans('messages.sandbox')],
					['value' => 'production', 'text' => trans('messages.production')],
				],
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
	</div>
	<div class="row">
		<div class="col-md-8">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[clientID]',
                'label' => trans('messages.paypal_client_id'),
				'value' => $payment_method->getOption('clientID'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
	</div>
	<div class="row">
		<div class="col-md-8">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[secret]',
                'label' => trans('messages.paypal_secret'),
				'value' => $payment_method->getOption('secret'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
	</div>
@endif

@if ($payment_method->type == \Acelle\Model\PaymentMethod::TYPE_BRAINTREE_PAYPAL ||
	$payment_method->type == \Acelle\Model\PaymentMethod::TYPE_BRAINTREE_CREDIT_CARD
)
    <div class="row">
        <div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'select',
				'name' => 'options[environment]',
                'label' => trans('messages.environment'),
				'value' => $payment_method->getOption('environment'),
				'options' => [
					['value' => 'sandbox', 'text' => trans('messages.sandbox')],
					['value' => 'production', 'text' => trans('messages.production')],
				],
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
		<div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[merchantId]',
                'label' => trans('messages.merchant_id'),
				'value' => $payment_method->getOption('merchantId'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
	</div>
	<div class="row">
		<div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[publicKey]',
                'label' => trans('messages.public_key'),
				'value' => $payment_method->getOption('publicKey'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
		<div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[privateKey]',
                'label' => trans('messages.private_key'),
				'value' => $payment_method->getOption('privateKey'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
    </div>
@endif

@if ($payment_method->type == \Acelle\Model\PaymentMethod::TYPE_STRIPE_CREDIT_CARD)
    <div class="row">
		<div class="col-md-4">
            @include('helpers.form_control', [
				'type' => 'text',
				'name' => 'options[api_key]',
                'label' => trans('messages.stripe_api_key'),
				'value' => $payment_method->getOption('api_key'),
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
        </div>
	</div>
@endif
