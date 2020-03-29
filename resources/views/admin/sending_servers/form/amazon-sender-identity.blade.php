<div class="mc_section boxing">
    <div class="row">
        <div class="col-md-6">
            <h3 class="mt-0">{{ trans('messages.sending_servers.sending_identity') }}</h3>
            <p>
                {!! trans('messages.sending_servers.sending_identity.intro', ['link' => 'https://us-west-2.console.aws.amazon.com/ses/home#verified-senders-domain']) !!}
            </p>
            @if (is_null($identities))
                @include('elements._notification', [
                    'level' => 'warning',
                    'title' => 'Error fetching identities list',
                    'message' => 'Please check your connection to AWS',
                ])
            @else
                <table class="table table-box table-box-head field-list">
                    <thead>
                        <tr>
                            <td>{{ trans('messages.domain') }}</td>
                            <td>{{ trans('messages.status') }}</td>
                            <td>{{ trans('messages.action') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($identities as $domain)
                            <tr class="odd">
                                <td>
                                    {{ $domain }}
                                </td>
                                <td>
                                    <span class="badge badge-success badge-lg">{{ trans('messages.sending_identity.status.active') }}</span>
                                </td>
                                <td>
                                    @if (checkEmail($domain))
                                        <input type="checkbox" name="options[emails][]" value="{{ $domain }}" class="switchery"
                                            {{ $server->isEmailEnabled($domain) ? " checked" : "" }}
                                        />
                                    @else
                                        <input type="checkbox" name="options[domains][]" value="{{ $domain }}" class="switchery"
                                            {{ $server->isDomainEnabled($domain) ? " checked" : "" }}
                                        />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <br>
            <a target="_blank" confirm="{{ trans('messages.sending_server.aws.add_domain_redirect_confirm') }}" href="https://us-west-2.console.aws.amazon.com/ses/home?region={{ $server->type }}" class="btn btn-mc_default mr-10">
                {{ trans('messages.sending_serbers.add_domain') }}
            </a>
            <a target="_blank" href="https://console.aws.amazon.com/ses/home?region={{ $server->aws_region }}" type="button"
              class="btn btn-mc_default">
                {{ trans('messages.sending_serbers.go_to_amazon_dashboard') }}
            </a>
            <p class="mt-40">
                {{ trans('messages.sending_serbers.aws.allow_verify.intro') }}
            </p>

            @include('helpers.form_control', [
                'type' => 'checkbox2',
                'label' => trans('messages.allow_verify_domain_against_aws'),
                'name' => 'options[allow_verify_domain_remotely]',
                'value' => $server->getOption('allow_verify_domain_remotely'),
                'help_class' => 'sending_server',
                'options' => ['no', 'yes'],
            ])

            @include('helpers.form_control', [
                'type' => 'checkbox2',
                'label' => trans('messages.allow_verify_email_against_aws'),
                'name' => 'options[allow_verify_email_remotely]',
                'value' => $server->getOption('allow_verify_email_remotely'),
                'help_class' => 'sending_server',
                'options' => ['no', 'yes'],
            ])

            <div class="use_custom_verification_email" style="display:none;padding-left:35px">
                @include('helpers.form_control', [
                    'type' => 'checkbox2',
                    'label' => trans('messages.use_custom_verification_email', [
                        'link' => action('Admin\SendingServerController@customVerificationEmail', $server->uid)
                    ]),
                    'name' => 'options[use_custom_verification_email]',
                    'value' => $server->getOption('use_custom_verification_email'),
                    'help_class' => 'sending_server',
                    'options' => ['no', 'yes'],
                ])
            </div>

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

<script>
    $(function() {
        $('[name="options[allow_verify_email_remotely]"]').change(function() {
            var value = $(this).is(':checked');
            if(value) {
                $('.use_custom_verification_email').show();
            } else {
                $('.use_custom_verification_email').hide();
            }
        });
        $('[name="options[allow_verify_email_remotely]"]').change();

        $('.custom-verification-email-edit').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');
            
            openBuilderClassic(url);
        });
    });
</script>
