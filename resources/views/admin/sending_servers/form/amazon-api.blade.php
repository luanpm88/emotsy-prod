<div class="mc_section">
    <h2>{{ trans('messages.' . $server->type) }}</h2>
        
    <p>{{ trans('messages.sending_servers.amazon_api_key.intro') }}</p>
    
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'class' => '',
                'name' => 'aws_access_key_id',
                'value' => $server->aws_access_key_id,
                'help_class' => 'sending_server',
                'rules' => Acelle\Model\SendingServer::rules(request()->type)
            ])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'password',
                'class' => '',
                'name' => 'aws_secret_access_key',
                'value' => $server->aws_secret_access_key,
                'help_class' => 'sending_server',
                'rules' => Acelle\Model\SendingServer::rules(request()->type),
                'eye' => true,
            ])
        </div>
    </div>
	<div class="text-left">
		@if ($server->id && Auth::user()->admin->can('test', $server))
			<a
				href="{{ action('Admin\SendingServerController@test', $server->uid) }}"
				type="button"
				class="btn bg-teal mr-5 btn-icon modal_link"
				data-in-form="true"
				data-method="GET"
			>
				<i class="icon-rotate-cw3"></i> {{ trans('messages.sending_server.test') }}
			</a>
		@endif
		<button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
		<a href="{{ action('Admin\SendingServerController@index') }}" type="button" class="btn btn-mc_inline">
			{{ trans('messages.cancel') }}
		</a>
	</div>
</div>
@if ($server->id || true)
    <div class="mc_section">
        <div class="row">
            <div class="col-md-6">
                <h3>{{ trans('messages.sending_servers.configuration_settings') }}</h3>
                <p>
                    {{ trans('messages.sending_servers.configuration_settings.intro') }}
                </p>

                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'name',
                    'value' => $server->name,
                    'help_class' => 'sending_server',
                    'rules' => Acelle\Model\SendingServer::rules(request()->type)
                ])

                <p>{{ trans('messages.sending_servers.sending_limit.aws.intro') }}</p>
                    
                @include('helpers.form_control', [
                    'type' => 'select',
                    'class' => '',
                    'name' => 'speed_limit',
                    'label' => trans('messages.sending_servers.speed_limit'),
                    'value' => $server->name,
                    'help_class' => 'sending_server',
                    'rules' => Acelle\Model\SendingServer::rules(request()->type),
                    'options' => Acelle\Model\SendingServer::getSendingLimitSelectOptions(),
                ])
                
                <div class="boxing">
                    <p>{!! trans('messages.options.wording') !!}</p>
                        
                    <div class="row">
                        <div class="col-md-4">
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'class' => 'numeric',
                                'name' => 'quota_value',
                                'value' => $server->quota_value,
                                'help_class' => 'sending_server',
                                'rules' => Acelle\Model\SendingServer::rules(request()->type),
                                'default_value' => '1000',
                            ])
                            <div class="checkbox inline unlimited-check text-semibold">
                                <label>
                                    <input{{ $server->quota_value  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
                                    {{ trans('messages.unlimited') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @include('helpers.form_control', [
                                'type' => 'text',
                                'class' => 'numeric',
                                'name' => 'quota_base',
                                'value' => $server->quota_base,
                                'help_class' => 'sending_server',
                                'rules' => Acelle\Model\SendingServer::rules(request()->type),
                                'default_value' => '1',
                            ])
                        </div>
                        <div class="col-md-4">
                            @include('helpers.form_control', ['type' => 'select',
                                'name' => 'quota_unit',
                                'value' => $server->quota_unit,
                                'label' => trans('messages.quota_time_unit'),
                                'options' => Acelle\Model\Plan::quotaTimeUnitOptions(),
                                'include_blank' => trans('messages.choose'),
                                'help_class' => 'sending_server',
                                'rules' => Acelle\Model\SendingServer::rules(request()->type)
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mc_section boxing">
        <div class="row">
            <div class="col-md-6">
                <h2>{{ trans('messages.sending_servers.sending_identity') }}</h2>
                <p>
                    {!! trans('messages.sending_servers.sending_identity.intro', ['link' => '']) !!}                    
                </p>
                <table class="table table-box table-box-head field-list">
                    <thead>
                        <tr>
                            <td>{{ trans('messages.created_at') }}</td>
                            <td>{{ trans('messages.domain') }}</td>
                            <td>{{ trans('messages.status') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="odd">
                            <td>Nov 20, 2018 9:55 pm</td>
                            <td>
                                acellemail.com
                            </td>
                            <td>
                                <input type="checkbox" name="my-checkbox" class="switchery" checked>
                            </td>
                        </tr>
                        <tr class="odd">
                            <td>Dec 05, 2018 10:43 am<br><span class="small-meta">Disabled on 12/05/2018</span></td>
                            <td>
                                bolero.vn
                            </td>
                            <td>
                                <input type="checkbox" name="my-checkbox" class="switchery" checked>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <button class="btn btn-mc_primary mr-10">{{ trans('messages.sending_serbers.add_domain') }}</button>
                <a href="{{ action('Admin\SendingServerController@index') }}" type="button"
                  class="btn btn-mc_default">
                    {{ trans('messages.sending_serbers.go_to_amazon_dashboard') }}
                </a>
                <p class="mt-40">
                    You can also allow your users to verify an identity on their own.
                    That is, user can request to verify their own email address or domain against your
                    Amazon SES account
                </p>
                
                @include('helpers.form_control', [
                    'type' => 'checkbox2',
                    'label' => 'Allow verifying user\'s own domains against AWS',
                    'name' => 'quota_base',
                    'value' => '',
                    'help_class' => 'sending_server',
                    'rules' => Acelle\Model\SendingServer::rules(request()->type),
                    'default_value' => '1',
                    'options' => ['no', 'yes'],
                ])
                @include('helpers.form_control', [
                    'type' => 'checkbox2',
                    'label' => 'Allow verifying user\'s own email addresses against AWS',
                    'name' => 'quota_base',
                    'value' => '',
                    'help_class' => 'sending_server',
                    'rules' => Acelle\Model\SendingServer::rules(request()->type),
                    'default_value' => '1',
                    'options' => ['no', 'yes'],
                ])
                
                <div class="mt-20">
                    <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
                    <a href="{{ action('Admin\SendingServerController@index') }}" type="button" class="btn btn-mc_inline">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif