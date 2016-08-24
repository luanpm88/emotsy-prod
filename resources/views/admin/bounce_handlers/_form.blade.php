					<h4 class="mt-0 text-semibold">{{ trans('messages.' . request()->type) }}</h4>
					
					<div class="row">
						<div class="col-sm-6 col-md-4">							
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'name',
								'value' => $server->name,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'host',
								'value' => $server->host,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'port',
								'value' => $server->port,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'username',
								'value' => $server->username,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'password',
								'value' => $server->password,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
						<div class="col-sm-6 col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => '',
								'name' => 'protocol',
								'value' => $server->protocol,
								'help_class' => 'bounce_handler',
								'rules' => Acelle\Model\BounceHandler::rules()
							])
						</div>
					</div>
					
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>