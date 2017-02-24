<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
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
			<div class="col-md-6">
				<div class="">
					@include('helpers.form_control', [
						'type' => 'select_ajax',
						'class' => 'subsciption-plan-select hook',
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
		<div class="row">
			<div class="col-md-6">
				<div class="">
					@include('helpers.form_control', [
						'type' => 'select',
						'name' => 'quantity',
						'label' => trans('messages.quantity'),
						'value' => $subscription->quantity,
						'class' => 'hook',
						'help_class' => 'subscription',
						'rules' => $subscription->rules(),
						'options' => \Acelle\Library\Tool::numberSelectOptions(),
						'placeholder' => trans('messages.select_plan')
					])
				</div>
			</div>
			<div class="col-md-6">
				<div class="">
					@include('helpers.form_control', [
						'type' => 'textarea',
						'name' => 'note',
						'label' => trans('messages.subscription_note'),
						'value' => $subscription->note,
						'help_class' => 'subscription',
						'rules' => $subscription->rules()
					])
				</div>
			</div>
		</div>		
	</div>
</div>
<div class="row">
	<div class="col-md-12 ajax-detail-box" data-url="{{ action('Admin\SubscriptionController@preview') }}" data-form=".subscription-form">
		@include('admin.subscriptions.preview', [
			'subscription' => $subscription
		])
	</div>
</div>
	
<hr />
<div class="text-right">
	<button type='submit' class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>