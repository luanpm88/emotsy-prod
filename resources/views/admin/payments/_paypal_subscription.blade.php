<div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs mc-nav campaign-template-tabs inline-tabs mt-4" role="tablist">
		<li role="presentation" class="{{ $isValid ? '' : 'active' }}"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">
			{{ trans('messages.payment.paypal_subscription.connection') }}
		</a></li>

		@if ($isValid)
			<li role="presentation" class="{{ !$isValid ? '' : 'active' }}"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
				{{ trans('messages.payment.paypal.connect_your_plans') }}
			</a></li>
		@endif
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane {{ $isValid ? '' : 'active' }}" id="home">
			<h2 class="mt-0">{{ trans('messages.payment.options') }}</h2>

			<form enctype="multipart/form-data" action="{{ action('Admin\PaymentController@update', $gateway['name']) }}" method="POST" class="form-validate-jquery">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-6">
						@include('helpers.form_control', [
							'type' => 'select',
							'name' => 'options[environment]',
							'value' => $gateway['fields']['environment'],
							'label' => trans('messages.payment.paypal.environment'),
							'help_class' => 'payment',
							'options' => [['text' => 'Sandbox', 'value' => 'sandbox'],['text' => 'Production', 'value' => 'production']],
							'rules' => ['options.environment' => 'required'],
						])

						@include('helpers.form_control', [
							'type' => 'text',
							'class' => '',
							'name' => 'options[client_id]',
							'value' => $gateway['fields']['client_id'],
							'label' => trans('messages.payment.paypal.client_id'),
							'help_class' => 'payment',
							'rules' => ['options.client_id' => 'required'],
						])	
						
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => '',
							'name' => 'options[secret]',
							'value' => $gateway['fields']['secret'],
							'label' => trans('messages.payment.paypal.secret'),
							'help_class' => 'payment',
							'rules' => ['options.secret' => 'required'],
						])
					</div>
				</div>

				<hr>
				<div class="text-left">
					<button class="btn btn-mc_primary mr-5">{{ trans('messages.save') }}</button>
					@if (\Acelle\Model\Setting::get('system.payment_gateway') !== $gateway['name'])
						<input type="submit" class="btn btn-mc_primary bg-teal  mr-5" name="save_and_set_primary" value="{{ trans('messages.save_and_set_primary') }}" />
					@endif
					<a class="btn btn-mc_default" href="{{ action('Admin\PaymentController@index') }}">{{ trans('messages.cancel') }}</a>
				</div>
			</form>
		</div>

		@if ($isValid)			
			<div role="tabpanel" class="tab-pane {{ !$isValid ? '' : 'active' }}" id="profile">		
				@if (!$service->getData()['product_id'])
					<div class="row mt-0">
						<div class="col-md-6">
							<h2 class="mb-3 mt-0">{{ trans('messages.payment.paypal_subscription.connect_to_paypal') }}</h2>
							<p>{!! trans('messages.payment.paypal_subscription.connect_to_paypal.wording', [
								'name' => \Acelle\Model\Setting::get('site_name'),
								'product_id' => $service->getData()['product_id'],
								'dashboard' => '',
								'disconnect' => action('Admin\PaymentController@paypalSubscriptionConnect', 'sss'),
							]) !!}<p>

							<form
								action="{{ action('Admin\PaymentController@paypalSubscriptionConnect') }}"
								method="POST" class="form-validate-jquery"
							>
								{{ csrf_field() }}
								<button class="btn btn-mc_primary ml-5" href="">
									{{ trans('messages.payment.paypal_subscription.connect') }}
								</button>
							</form>
						</div>
					</div>
				@else
					<h2 class="mt-0">{{ trans('messages.payment.paypal.connect_your_plans') }}</h2>
					<p>{{ trans('messages.payment.paypal.connect_your_plans.intro') }}<p>

					<div class="mc-list mt-20">
						@foreach (Acelle\Model\Plan::getActive()->get() as $plan)
							@if ($plan->price > 0)
								<div class="list-item d-flex align-items-center">
									<div class="list-setting-main mr-auto" style="width: 50%">
										<h4 class="title text-bold">
											{{ $plan->name }}
										</h4>
										<p>{{ $plan->description }}</p>
									</div>
									<div class="list-setting-main mr-auto">
										<h4 class="title text-bold">
											{{ $plan->getBillableFormattedPrice() }}
										</h4>
										<p class="text-muted">{{ $plan->displayFrequencyTime() }}</p>
									</div>
									<div class="list-setting-footer text-right" style="width: 20%">

										@if ($service->findPlanConnection($plan))
											<span class="">{{ trans('messages.payment.paypal_subscription.plan.connected') }}</span>
											<a link-method="POST" link-confirm="{{ trans('messages.payment.paypal_subscription.disconnect_plan.confirm') }}"
												class="btn btn-mc_default ml-5"
												href="{{ action('Admin\PaymentController@paypalSubscriptionDisconnectPlan', $plan->uid) }}"
											>
												{{ trans('messages.payment.paypal_subscription.disconnect_plan') }}
											</a>
										@else
											<form
												action="{{ action('Admin\PaymentController@paypalSubscriptionConnectPlan', $plan->uid) }}"
												method="POST" class="form-validate-jquery"
											>
												{{ csrf_field() }}
												<button class="btn btn-mc_primary ml-5" href="">
													{{ trans('messages.payment.paypal_subscription.connect_plan') }}
												</button>
											</form>
										@endif
										
									</div>
								</div>
							@endif
						@endforeach
					</div>

					<div class="row mb-4 mt-4">
						<div class="col-md-6">							
							<h2 class="mb-3">{{ trans('messages.payment.paypal_subscription.connected_to_paypal') }}</h2>
							<p>{!! trans('messages.payment.paypal_subscription.connected_to_paypal.wording', [
								'name' => \Acelle\Model\Setting::get('site_name'),
								'product_id' => $service->getData()['product_id'],
								'dashboard' => '',
								'disconnect' => action('Admin\PaymentController@paypalSubscriptionDisconnect', 'sss'),
							]) !!}<p>							
						</div>
					</div>
				@endif				
			</div>
		@endif
	</div>
</div>