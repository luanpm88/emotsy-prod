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
                        <td>{{ trans('messages.domain') }}</td>
                        <td>{{ trans('messages.status') }}</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($server->getVerifiedIdentities() as $domain)
                        <tr class="odd">
                            <td>
                                {{ $domain }}
                            </td>
                            <td>
                                <input type="checkbox" name="options[domains][]" value="{{ $domain }}" class="switchery"
                                    {{ $server->isDomainEnabled($domain) ? " checked" : "" }}
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <a href="https://app.sendgrid.com" type="button" target="_blank"
              class="btn btn-mc_default">
                {{ trans('messages.sending_serbers.go_to_sendgrid_dashboard') }}
            </a>

            <p class="mt-40">
                {{ trans('messages.sending_serbers.sendgrid.allow_verify.intro') }}
            </p>

            @include('helpers.form_control', [
                'type' => 'checkbox2',
                'label' => trans('messages.allow_verify_domain_against_sendgrid'),
                'name' => 'options[allow_verify_domain_remotely]',
                'value' => $server->getOption('allow_verify_domain_remotely'),
                'help_class' => 'sending_server',
                'options' => ['no', 'yes'],
            ])
            @include('helpers.form_control', [
                'type' => 'checkbox2',
                'label' => trans('messages.allow_verify_email_against_sendgrid'),
                'name' => 'options[allow_verify_email_remotely]',
                'value' => $server->getOption('allow_verify_email_remotely'),
                'help_class' => 'sending_server',
                'options' => ['no', 'yes'],
                'disabled' => 'disabled',
            ])

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
