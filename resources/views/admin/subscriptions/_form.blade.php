<div class="row">						
	<div class="col-md-4">
		<div class="">
			@include('helpers.form_control', [
				'type' => 'select_ajax',
				'name' => 'customer_id',
				'label' => trans('messages.customer'),
				'selected' => [
					'value' => $subscription->customer_id,
					'text' => is_object($subscription->customer) ? $subscription->customer->displayName() : ''
				],
				'help_class' => 'subscription',
				'rules' => $subscription->rules(),
				'url' => action('Admin\CustomerController@select2'),
				'placeholder' => trans('messages.select_customer')
			])
		</div>
	</div>
	<div class="col-md-4">
		<div class="">
			@include('helpers.form_control', [
				'type' => 'select_ajax',
				'name' => 'plan_id',
				'label' => trans('messages.plan'),
				'selected' => [
					'value' => $subscription->plan_id,
					'text' => is_object($subscription->plan) ? $subscription->plan->name : ''
				],
				'help_class' => 'subscription',
				'rules' => $subscription->rules(),
				'url' => action('Admin\PlanController@select2'),
				'placeholder' => trans('messages.select_plan')
			])
		</div>
	</div>
</div>
	
<hr />
<div class="text-left">
	<button type='submit' class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>