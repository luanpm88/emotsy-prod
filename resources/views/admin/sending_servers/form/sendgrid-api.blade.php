<form action="{{ action('Admin\SendingServerController@store', ["type" => request()->type]) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="{{ $server->type }}" />
    
    <div class="mc_section">
        <h2>{{ trans('messages.' . $server->type) }}</h2>
            
        <p>{{ trans('messages.sending_servers.sendgrid_api_key.intro') }}</p>
        
        <div class="row">
            <div class="col-md-6">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'api_key',
                    'value' => $server->api_key,
                    'help_class' => 'sending_server',
                    'rules' => $server->getRules(),
                    'disabled' => $server->id,
                ])
            </div>
        </div>
        <div class="text-left">
            @if ($server->id && Auth::user()->admin->can('test', $server))
                <span class="edit-group">
                    <a
                        href="{{ action('Admin\SendingServerController@test', $server->uid) }}"
                        type="button"
                        class="btn btn-mc_primary mr-10 modal_link"
                        data-in-form="true"
                        data-method="GET"
                    >
                        {{ trans('messages.sending_server.test_connection') }}
                    </a>
                        <a
                        href="{{ action('Admin\SendingServerController@test', $server->uid) }}"
                        type="button"
                        class="btn btn-mc_default mr-10 modal_link"
                        data-in-form="true"
                        data-method="GET"
                    >
                        {{ trans('messages.sending_server.send_a_test_email') }}
                    </a>
                </span>
                <span class="cancel-group hide">
                    <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
                </span>
                <a href="javascript:;" type="button" class="btn btn-mc_inline switch-form-toggle"
                    data-disabled="true"
                    data-edit="{{ trans('messages.edit') }}"
                    data-cancel="{{ trans('messages.cancel') }}">
                        {{ trans('messages.edit') }}
                </a>
            @else
                <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\SendingServerController@index') }}" type="button" class="btn btn-mc_inline">
                    {{ trans('messages.cancel') }}
                </a>
            @endif
            
        </div>
    </div>
</form>
@if ($server->id)
<form action="{{ action('Admin\SendingServerController@config', $server->uid) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    <div class="mc_section">
        <div class="row">
            <div class="col-md-6">
                <h2 class=" mt-20">{{ trans('messages.sending_servers.configuration_settings') }}</h2>
                <p>
                    {{ trans('messages.sending_servers.configuration_settings.sendgrid.intro') }}
                </p>

                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'name',
                    'value' => $server->name,
                    'help_class' => 'sending_server',
                    'rules' => $server->getConfigRules(),
                ])
                
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'domain',
                    'value' => $server->domain,
                    'help_class' => 'sending_server',
                    'rules' => $server->getConfigRules(),
                ])
                
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'name' => 'default_from_email',
                    'value' => $server->default_from_email,
                    'help_class' => 'sending_server',
                    'rules' => $server->getConfigRules(),
                ])

                <p>{!! trans('messages.sending_servers.sending_limit.mailgun.intro') !!}</p>
                    
                <div class="select-custom" data-url="{{ action('Admin\SendingServerController@sendingLimit', ['uid' => ($server->uid ? $server->uid : 0)]) }}">
                    @include ('admin.sending_servers.form._sending_limit', [
                        'quotaValue' => $server->quota_value,
                        'quotaBase' => $server->quota_base,
                        'quotaUnit' => $server->quota_unit,
                    ])
                </div>
                
            </div>
        </div>
    </div>
    <div class="mc_section boxing">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mt-0">{{ trans('messages.sending_servers.sending_identity') }}</h3>
                <p>
                    {!! trans('messages.sending_servers.sending_identity.sendgrid.intro', ['link' => '']) !!}                    
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
                        @foreach ($server->getMailgunDomainInfo() as $domain)
                            <tr class="odd">
                                <td>
                                    {{ $domain['created_at']->diffForHumans() }}
                                    <br><span class="small-meta">Disabled on 12/05/2018</span>
                                    <input type="hidden" name="options[domains][{{ $domain['domain'] }}][created_at]" value="{{ $domain['created_at'] }}"
                                        
                                    />
                                    <input type="hidden" name="options[domains][{{ $domain['domain'] }}][enabled]" value="no" />
                                </td>
                                <td>
                                    {{ $domain['domain'] }}
                                </td>
                                <td>
                                    <input type="checkbox" name="options[domains][{{ $domain['domain'] }}][enabled]" value="yes" class="switchery"
                                        {{ $server->isDomainEnabled($domain['domain']) ? " checked" : "" }}
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <button class="btn btn-mc_default mr-10">{{ trans('messages.sending_serbers.add_domain') }}</button>
                <a href="{{ action('Admin\SendingServerController@index') }}" type="button"
                  class="btn btn-mc_default">
                    {{ trans('messages.sending_serbers.go_to_amazon_dashboard') }}
                </a>
                
                <hr>
                <div class="mt-20">
                    <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
                    <a href="{{ action('Admin\SendingServerController@index') }}" type="button" class="btn btn-mc_inline">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endif
