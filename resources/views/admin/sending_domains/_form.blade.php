					<h4 class="mt-0 text-semibold">{{ trans('messages.' . request()->type) }}</h4>
					
					<div class="row">
						<div class="col-sm-6 col-md-6">							
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'name',
								'value' => $server->name,
								'help_class' => 'sending_server',
								'rules' => Mailixa\Model\SendingDomain::rules()
							])
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="form-group checkbox-right-switch">
								@include('helpers.form_control', [
									'type' => 'checkbox',
									'class' => '',
									'name' => 'signing_enabled',
									'value' => $server->signing_enabled,
									'help_class' => 'sending_domain',
									'options' => [false, true],
									'rules' => Mailixa\Model\SendingDomain::rules()
								])
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-6">
							@include('helpers.form_control', [
								'type' => 'textarea',
								'readonly' => 'readonly',
								'name' => 'dkim_private',
								'value' => $server->dkim_private,
								'help_class' => 'sending_domain',
								'rules' => Mailixa\Model\SendingDomain::rules()
							])
						</div>
						<div class="col-sm-6 col-md-6">
							@include('helpers.form_control', [
								'type' => 'textarea',
								'readonly' => 'readonly',
								'name' => 'dkim_public',
								'value' => $server->dkim_public,
								'help_class' => 'sending_domain',
								'rules' => Mailixa\Model\SendingDomain::rules()
							])
						</div>						
					</div>
					
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>