<div class="row">						
	<div class="col-md-4">
		<div class="">
			@include('helpers.form_control', [
				'type' => 'text',
				'name' => 'name',
				'value' => $payment_method->name,
				'help_class' => 'payment_method',
				'rules' => $payment_method->rules()
			])
		</div>
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', ['type' => 'select',
			'name' => 'type',
			'value' => $payment_method->type,
			'class' => 'hook',
			'label' => trans('messages.payment_method_type'),
			'options' => Acelle\Model\PaymentMethod::typeSelectOptions(),
			'include_blank' => trans('messages.choose'),
			'help_class' => 'payment_method',
			'rules' => $payment_method->rules()
		])
	</div>	
</div>
<div class="row">
	<div class="col-md-12 ajax-detail-box" data-url="{{ action('Admin\PaymentMethodController@options') }}" data-form=".payment-method-form">
		@include('admin.payment_methods._options', [
			'payment_method' => $payment_method
		])
	</div>
</div>

<hr />
<div class="text-left">
	<button type='submit' class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>	