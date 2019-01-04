<div class="mc_section boxing">
    <div class="row">
        <div class="col-md-6">
            <h3 class="mt-0">{{ trans('messages.sending_servers.sending_identity') }}</h3>
            <p>
                {!! trans('messages.sending_servers.sending_identity.mailgun.intro', ['link' => '']) !!}                    
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
                {{ trans('messages.sending_serbers.go_to_mailgun_dashboard') }}
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