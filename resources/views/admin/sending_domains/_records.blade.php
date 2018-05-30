<table class="table table-trans tbody-white" class="table-layout:fixed">
    <thead>
        <tr>
            <th class="trans-upcase text-semibold">{{ trans('messages.type') }}</th>
            <th class="trans-upcase text-semibold">{{ trans('messages.host') }}</th>
            <th class="trans-upcase text-semibold">{{ trans('messages.value') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody class="bg-white">
        <tr>
            <td width="1%">TXT</td>
            <td width="20%">{{ $server->getVerificationTxtName() }}</td>
            <td>{{ $server->verification_token }}</td>
            <td class="text-right" width="1%"><a href="javascript:;" class="btn btn-default">{{ trans('messages.copy') }}</a></td>
        </tr>
        <tr>
            <td>TXT</td>
            <td>mailer._domainkey</td>
            <td><textarea style="width:100%;border:0;height:100px;resize:none;">{{ $server->getDnsDkimConfig() }}</textarea></td>
            <td class="text-right"><a href="javascript:;" class="btn btn-default">{{ trans('messages.copy') }}</a></td>
        </tr>
    </tbody>
</table>
    
<div class="text-right mt-20">
    <!--<a callback="" data-method="POST" href="{{ action('Admin\SendingDomainController@verify', $server->uid) }}" class="btn btn-primary ajax_link">{{ trans('messages.sending_domain_verify') }}</a>-->
    <a onclick="tableRefreshAll();" class="btn btn-primary">{{ trans('messages.sending_domain_verify') }}</a>
</div>