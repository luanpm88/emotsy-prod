<form enctype="multipart/form-data" action="{{ action('Admin\PaymentController@update', $gateway['name']) }}" method="POST" class="braintree form-validate-jquery">
    {{ csrf_field() }}
	<div class="sub-section">
		<div class="row">
			<div class="col-md-6">
				@include('helpers.form_control', [
					'type' => 'select',
					'name' => 'options[environment]',
					'value' => $gateway['fields']['environment'],
					'label' => trans('messages.payment.braintree.environment'),
					'help_class' => 'payment',
					'options' => [['text' => 'Sandbox', 'value' => 'sandbox'],['text' => 'Production', 'value' => 'production']],
					'rules' => ['options.environment' => 'required'],
				])
				
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'options[merchant_id]',
					'value' => $gateway['fields']['merchant_id'],
					'label' => trans('messages.payment.braintree.merchant_id'),
					'help_class' => 'payment',
					'rules' => ['options.merchant_id' => 'required'],
				])
				
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'options[public_key]',
					'value' => $gateway['fields']['public_key'],
					'label' => trans('messages.payment.braintree.public_key'),
					'help_class' => 'payment',
					'rules' => ['options.public_key' => 'required'],
				])
				
				@include('helpers.form_control', [
					'type' => 'text',
					'name' => 'options[private_key]',
					'value' => $gateway['fields']['private_key'],
					'label' => trans('messages.payment.braintree.private_key'),
					'help_class' => 'payment',
					'rules' => ['options.private_key' => 'required'],
				])
				
				<hr>
				<div class="text-left">
					<button class="btn btn-mc_primary mr-5">{{ trans('messages.save') }}</button>
					@if (\Acelle\Model\Setting::get('system.payment_gateway') !== $gateway['name'])
						<input type="submit" class="btn btn-mc_primary bg-teal  mr-5" name="save_and_set_primary" value="{{ trans('messages.save_and_set_primary') }}" />
					@endif
					<a class="btn btn-mc_default" href="{{ action('Admin\PaymentController@index') }}">{{ trans('messages.cancel') }}</a>
				</div>
			</div>
		</div>
	</div>
</form>


@if($service->isValid())
	<?php
		$remotePlans = $service->serviceGateway->plan()->all();
		$mapping = json_decode(config('cashier.gateways.braintree.fields.mapping'), true);
	?>
	<form enctype="multipart/form-data" action="{{ action('Admin\PaymentController@braintreeMapping') }}" method="POST" class="mapping form-validate-jquery">
		{{ csrf_field() }}
		
		<div class="sub-section">
			<div class="row">
				<div class="col-md-6">
					<h2>{{ trans('messages.braintree.plan_mapping') }}</h2>
					<p>{{ trans('messages.braintree.plan_mapping.intro') }}</p>
						
					<ul class="dotted-list topborder section mb-20">
						@foreach(\Acelle\Model\Plan::getAll()->get() as $plan)
							<li class="flex">
								<div class="unit size1of2">
									<strong>{{ $plan->name }}</strong>
								</div>
								<div class="lastUnit size1of2">
									<select class="form-control" name="mapping[{{ $plan->uid }}]" style="display: inline-block; width: 200px;">
										<option value=''>{{ trans('messages.select_plan') }}</option>
										@foreach ($remotePlans as $remotePlan) {
											<option {{ isset($mapping[$plan->uid]) && $mapping[$plan->uid] == $remotePlan->id ? 'selected="selected"' : '' }} value="{{ $remotePlan->id }}">{{ $remotePlan->name }}</option>
										@endforeach
									</select>
								</div>
							</li>
						@endforeach
					</ul>
						
					<div class="text-left">
						<button class="btn btn-mc_primary mr-5">{{ trans('messages.save') }}</button>
						<a class="btn btn-mc_default" href="{{ action('Admin\PaymentController@index') }}">{{ trans('messages.cancel') }}</a>
					</div>
				</div>
			</div>
		</div>
@endif

<script>
	$(document).ready(function() {
		$(document).on('change keyup', 'form.braintree input, form.braintree select', function() {
			$('form.mapping').hide();
		});
	});
</script>