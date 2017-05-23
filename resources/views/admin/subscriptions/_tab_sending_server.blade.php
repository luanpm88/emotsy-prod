<h4 class="text-teal-800 text-semibold">{{ trans('messages.sending_servers_settings') }}</h4>

<div class="row">
    <div class="col-md-5">
        <span class="text-semibold">{{ trans('messages.allow_customer_create_sending_servers') }}</span> &nbsp;&nbsp;&nbsp;
        <span class="notoping">
            @include('helpers.form_control', ['type' => 'checkbox',
                'class' => '',
                'name' => 'options[create_sending_servers]',
                'value' => $options['create_sending_servers'],
                'label' => '',
                'options' => ['no','yes'],
                'help_class' => 'subscription',
                'rules' => $subscription->rules()
            ])
        </span>
    </div>
</div>
<hr>

<div class="sending-servers-yes">
    <div class="row">
        <div class="col-md-4">
            <div class="boxing pb-0">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => 'numeric',
                    'name' => 'options[sending_servers_max]',
                    'value' => $options['sending_servers_max'],
                    'label' => trans('messages.max_sending_servers'),
                    'help_class' => 'subscription',
                    'options' => ['true', 'false'],
                    'rules' => $subscription->rules()
                ])
                <div class="checkbox inline unlimited-check text-semibold mb-0">
                    <label>
                        <input{{ $options['sending_servers_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
                        {{ trans('messages.unlimited') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h5 class="text-teal-800 text-semibold">{{ trans('messages.allowed_sending_server_types') }}</h5>
	<div class="row">
		<div class="col-md-5">
			<span class="text-semibold">{{ trans('messages.allow_customer_create_sending_servers') }}</span> &nbsp;&nbsp;&nbsp;
			<span class="notoping">
				@include('helpers.form_control', ['type' => 'checkbox',
					'class' => '',
					'name' => 'options[all_sending_server_types]',
					'value' => $options['all_sending_server_types'],
					'label' => '',
					'options' => ['no','yes'],
					'help_class' => 'subscription',
					'rules' => $subscription->rules()
				])
			</span>
		</div>
	</div>
	<div class="all_sending_server_types_no">
		<hr>
		<label class="text-semibold">{{ trans('messages.select_allowed_sending_server_types') }}</label>
		<div class="row">
			@foreach (Acelle\Model\SendingServer::types() as $key => $type)
				<div class="col-md-4 pt-10">
					&nbsp;&nbsp;<span class="text-semibold text-italic">{{ trans('messages.' . $key) }}</span>
					<span class="notoping pull-left">
						@include('helpers.form_control', ['type' => 'checkbox',
							'class' => '',
							'name' => 'options[sending_server_types][' . $key . ']',
							'value' => isset($options['sending_server_types'][$key]) ? $options['sending_server_types'][$key] : 'no',
							'label' => '',
							'options' => ['no','yes'],
							'help_class' => 'subscription',
							'rules' => $subscription->rules()
						])
					</span>
				</div>
			@endforeach
		</div>
	</div>
</div>

<div class="sending-servers-no">
    <h5 class="text-semibold">{{ trans('messages.setting_up_sending_servers_for_subscription') }}</h5>
    <div class="row">
        <div class="col-md-3">
            {{ trans('messages.use_all_sending_servers') }}&nbsp;&nbsp;&nbsp;
            <span class="notoping">
                @include('helpers.form_control', ['type' => 'checkbox',
                    'class' => '',
                    'name' => 'options[all_sending_servers]',
                    'value' => $options['all_sending_servers'],
                    'label' => '',
                    'options' => ['no','yes'],
                    'help_class' => 'subscription',
                    'rules' => $subscription->rules()
                ])
            </span>

        </div>
    </div>
    @if(!Acelle\Model\SendingServer::getAllAdminActive()->count())
        <div class="empty-list">
            <i class="icon-server"></i>
            <span class="line-1">
                {{ trans('messages.sending_server_no_active') }}
            </span>
        </div>
    @endif
    <br />
    <div class="row sending-servers">
        @foreach (Acelle\Model\SendingServer::getAllAdminActive()->orderBy("name")->get() as $server)
            <div class="col-md-6">
                <h5 class="mt-0 mb-5 text-semibold text-teal-600">{{ $server->name }}</h5>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="mt-0 mb-5 text-semibold">{{ trans('messages.choose') }}</label>
                            @include('helpers.form_control', [
                                'type' => 'checkbox',
                                'name' => 'sending_servers[' . $server->uid . '][check]',
                                'value' => $subscription->subscriptionsSendingServers->contains('sending_server_id', $server->id),
                                'label' => '',
                                'options' => [false, true],
                                'help_class' => 'subscription',
                                'rules' => $subscription->rules()
                            ])
                        </div>
                        <br><br>
                    </div>
                    <div class="col-md-9">
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'class' => 'numeric',
                            'name' => 'sending_servers[' . $server->uid . '][fitness]',
                            'label' => trans('messages.fitness'),
                            'value' => (is_object($subscription->subscriptionsSendingServers()->where('sending_server_id', $server->id)->first()) ? $subscription->subscriptionsSendingServers()->where('sending_server_id', $server->id)->first()->fitness : "100"),
                            'help_class' => 'subscription',
                            'rules' => $subscription->rules()
                        ])
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
