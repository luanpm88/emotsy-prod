@if ($errors->has('payment_method_not_valid'))
	<div class="alert alert-danger">{{ $errors->first('payment_method_not_valid') }}</div>
@endif
<div class="row">
	<div class="col-md-4">
		@include('helpers.form_control', ['type' => 'select',
			'name' => 'status',
			'value' => $payment_method->status,
			'class' => '',
			'label' => trans('messages.payment_method_status'),
			'options' => Acelle\Model\PaymentMethod::statusSelectOptions(),
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
