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
						@if (array_key_exists("aws_access_key_id", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'aws_access_key_id',
									'value' => $server->aws_access_key_id,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("aws_secret_access_key", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'aws_secret_access_key',
									'value' => $server->aws_secret_access_key,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("aws_region", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'aws_region',
									'value' => $server->aws_region,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("smtp_username", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'smtp_username',
									'value' => $server->smtp_username,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("smtp_password", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'smtp_password',
									'value' => $server->smtp_password,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("smtp_port", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'smtp_port',
									'value' => $server->smtp_port,
									'help_class' => 'sending_server',
									'rules' => Mailixa\Model\SendingServer::rules(request()->type)
								])
							</div>
						@endif
						@if (array_key_exists("smtp_protocol", Mailixa\Model\SendingServer::types()[request()->type]["cols"]))
							<div class="col-sm-6 col-md-4">
								@include('helpers.form_control', [
									'type' => 'text',
									'class' => '',
									'name' => 'smtp_protocol',
									'value' => $server->smtp_protocol,
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
						
					<h4 class="text-semibold text-teal-800">{{ trans('messages.handlers') }}</h4>
					<div class="row">
						<div class="col-md-4">
							@include('helpers.form_control', [
								'type' => 'select',
								'class' => '',
								'name' => 'bounce_handler_id',
								'label' => trans("messages.bounce_handler"),
								'value' => $server->bounce_handler_id,
								'help_class' => 'sending_server',
								'include_blank' => trans('messages.choose'),
								'options' => Mailixa\Model\BounceHandler::getSelectOptions(),
								'rules' => Mailixa\Model\SendingServer::rules(request()->type)
							])
						</div>
						<div class="col-md-4">
							@include('helpers.form_control', [
								'type' => 'select',
								'class' => '',
								'name' => 'feedback_loop_handler',
								'label' => trans("messages.feedback_loop_handler"),
								'value' => $server->feedback_loop_handler,
								'help_class' => 'sending_server',
								'include_blank' => trans('messages.choose'),
								'options' => Mailixa\Model\FeedbackLoopHandler::getSelectOptions(),
								'rules' => Mailixa\Model\SendingServer::rules(request()->type)
							])
						</div>
					</div>
					
					<br >
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>