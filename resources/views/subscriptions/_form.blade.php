<div class="row">
	<div class="col-md-6">
		<div class="row">                                    
			<div class="col-md-6">
				<div class="">
					@include('helpers.form_control', [
						'type' => 'select_ajax',
						'class' => 'subsciption-plan-select',
						'name' => 'plan_id',
						'label' => trans('messages.plan'),
						'selected' => [
							'value' => $subscription->plan_id,
							'text' => is_object($subscription->plan) ? $subscription->plan->name : 'sss'
						],
						'help_class' => 'subscription',
						'rules' => $subscription->rules(),
						'url' => action('PlanController@select2'),
						'placeholder' => trans('messages.select_plan')
					])
				</div>
			</div>
			<div class="col-md-6">
				<div class="">
					@include('helpers.form_control', [
						'type' => 'select',
						'name' => 'quantity',
						'label' => trans('messages.quantity'),
						'value' => $subscription->quantity,
						'help_class' => 'subscription',
						'rules' => $subscription->rules(),
						'options' => \Acelle\Library\Tool::numberSelectOptions(),
						'placeholder' => trans('messages.select_plan')
					])
				</div>                                        
			</div>
			<div class="col-md-12">
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
		<div class="ajax-detail-box plan-left-preview" data-url="{{ action('SubscriptionController@preview') }}" data-form=".subscription-form">
			@include('subscriptions.preview', [
				'subscription' => $subscription
			])
		</div>
	</div>
	<div class="col-md-6 ajax-detail-box plan-right-preview" data-url="{{ action('SubscriptionController@preview') }}" data-form=".subscription-form">
		@include('subscriptions.preview', [
			'subscription' => $subscription
		])
	</div>
</div> 