					<h4 class="mt-0 text-semibold text-teal-800">{{ trans('messages.' . request()->type) }}</h4>
					
					<div class="row">
						@if (array_key_exists("name", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">							
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'name',
									'value' => $server->name,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("host", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'host',
									'value' => $server->host,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("port", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'port',
									'value' => $server->port,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("username", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'username',
									'value' => $server->username,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("password", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'password',
									'value' => $server->password,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("protocol", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'protocol',
									'value' => $server->protocol,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("sendmail_path", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'sendmail_path',
									'value' => $server->sendmail_path,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
					</div>

					<h4 class="text-semibold text-teal-800">{{ trans('messages.sending_quota') }}</h4>
					
					<div class="row">
						<div class="col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => 'numeric',
								'name' => 'quota_value',
								'value' => $server->quota_value,
								'help_class' => 'sending_server',
								'rules' => Mailixa\Model\SendingServer::rules(request()->type)
							])
						</div>
						<div class="col-md-4">
							@include('helpers.form_control', [
								'type' => 'text',
								'class' => 'numeric',
								'name' => 'quota_base',
								'value' => $server->quota_base,
								'help_class' => 'sending_server',
								'rules' => Mailixa\Model\SendingServer::rules(request()->type)
							])
						</div>
						<div class="col-md-4">
							@include('helpers.form_control', ['type' => 'select',
								'name' => 'quota_unit',
								'value' => $server->quota_unit,
								'label' => trans('messages.quota_time_unit'),
								'options' => Mailixa\Model\UserGroup::timeUnitOptions(),
								'include_blank' => trans('messages.choose'),
								'help_class' => 'sending_server',
								'rules' => Mailixa\Model\SendingServer::rules(request()->type)
							])
						</div>
					</div>
					<br >
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>