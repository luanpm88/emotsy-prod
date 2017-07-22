<div class="form-group control-radio">
    <div class="radio_box" data-popup='tooltip' title="">
        <label class="main-control">
            <input type="radio" name="queue_driver" value="database" class="styled" />
            {{ trans('messages.plan_option.system_s_sending_server') }}
            <div class="desc text-normal mb-10">
                {{ trans('messages.plan_option.system_s_sending_server.intro') }}
            </div>
        </label>
        <div class="radio_more_box">
            <div class="row">
                <div class="col-md-3">
                    <span class="notoping">
                        @include('helpers.form_control', ['type' => 'checkbox2',
                            'class' => '',
                            'name' => 'options[all_sending_servers]',
                            'value' => $options['all_sending_servers'],
                            'label' => trans('messages.use_all_sending_servers'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->rules()
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
            <div class="sending-servers">
                <hr>
                <div class="row text-muted">
                    <div class="col-md-4">
                        <label>{{ trans('messages.select_sending_servers') }}</label>
                    </div>
                    <div class="col-md-4">
                        <label>{{ trans('messages.fitness') }}</label>
                    </div>
                </div>
                @foreach (Acelle\Model\SendingServer::getAllAdminActive()->orderBy("name")->get() as $server)
                    <div class="row mb-5">
                        <div class="col-md-4">
                            @include('helpers.form_control', [
                                'type' => 'checkbox2',
                                'name' => 'sending_servers[' . $server->uid . '][check]',
                                'value' => $plan->plansSendingServers->contains('sending_server_id', $server->id),
                                'label' => $server->name,
                                'options' => [false, true],
                                'help_class' => 'plan',
                                'rules' => $plan->rules()
                            ])
                        </div>
                        <div class="col-md-4">
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'class' => 'numeric',
                                'name' => 'sending_servers[' . $server->uid . '][fitness]',
                                'label' => '',
                                'value' => (is_object($plan->plansSendingServers()->where('sending_server_id', $server->id)->first()) ? $plan->plansSendingServers()->where('sending_server_id', $server->id)->first()->fitness : "100"),
                                'help_class' => 'plan',
                                'rules' => $plan->rules()
                            ])
                        </div>
                    </div>
                    <div class="col-md-6 hide">
                        <h5 class="mt-0 mb-5 text-semibold text-teal-600">{{ $server->name }}</h5>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="mt-0 mb-5 text-semibold">{{ trans('messages.choose') }}</label>
                                    @include('helpers.form_control', [
                                        'type' => 'checkbox',
                                        'name' => 'sending_servers[' . $server->uid . '][check]',
                                        'value' => $plan->plansSendingServers->contains('sending_server_id', $server->id),
                                        'label' => '',
                                        'options' => [false, true],
                                        'help_class' => 'plan',
                                        'rules' => $plan->rules()
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
                                    'value' => (is_object($plan->plansSendingServers()->where('sending_server_id', $server->id)->first()) ? $plan->plansSendingServers()->where('sending_server_id', $server->id)->first()->fitness : "100"),
                                    'help_class' => 'plan',
                                    'rules' => $plan->rules()
                                ])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <hr>
    <div class="radio_box" data-popup='tooltip' title="">
        <label class="main-control">
            <input type="radio" name="queue_driver" value="async" class="styled" />
            {{ trans('messages.plan_option.own_sending_server') }}
            <div class="desc text-normal mb-10">
                {{ trans('messages.plan_option.own_sending_server.intro') }}
            </div>
        </label>
        <div class="radio_more_box">
            <span class="boxing">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => 'numeric',
                    'name' => 'options[sending_servers_max]',
                    'value' => $options['sending_servers_max'],
                    'label' => trans('messages.max_sending_servers'),
                    'help_class' => 'plan',
                    'options' => ['true', 'false'],
                    'rules' => $plan->rules(),
                    'unlimited_check' => true,
                ])
            </span>

            <div class="row mt-20">
                <div class="col-md-5">
                    <span class="">
                        @include('helpers.form_control', ['type' => 'checkbox2',
                            'class' => '',
                            'name' => 'options[all_sending_server_types]',
                            'value' => $options['all_sending_server_types'],
                            'label' => trans('messages.allow_adding_all_sending_server_types'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->rules()
                        ])
                    </span>
                </div>
            </div>
            <div class="all_sending_server_types_no">
                <hr>
                <label class="text-semibold text-muted">{{ trans('messages.select_allowed_sending_server_types') }}</label>
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
                                    'help_class' => 'plan',
                                    'rules' => $plan->rules()
                                ])
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="radio_box" data-popup='tooltip' title="">
        <label class="main-control">
            <input type="radio" name="queue_driver" value="async" class="styled" />
            {{ trans('messages.plan_option.sub_account') }}
            <div class="desc text-normal mb-10">
                {{ trans('messages.plan_option.sub_account.intro') }}
            </div>
        </label>
        <div class="radio_more_box">
            <span class="boxing">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => 'numeric',
                    'name' => 'options[sending_servers_max]',
                    'value' => $options['sending_servers_max'],
                    'label' => trans('messages.max_sending_servers'),
                    'help_class' => 'plan',
                    'options' => ['true', 'false'],
                    'rules' => $plan->rules(),
                    'unlimited_check' => true,
                ])
            </span>
        </div>
    </div>
</div>
