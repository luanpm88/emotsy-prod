<table class="table table-trans tbody-white">
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
            <td>CNAME</td>
            <td>000.sample.com.vn</td>
            <td>MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIHBetcyn</td>
            <td class="text-right"><a href="javascript:;" class="btn btn-default">{{ trans('messages.copy') }}</a></td>
        </tr>
        <tr>
            <td>CNAME</td>
            <td>000.sample.com.vn</td>
            <td>MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDIHBetcyn</td>
            <td class="text-right"><a href="javascript:;" class="btn btn-default">{{ trans('messages.copy') }}</a></td>
        </tr>
    </tbody>
</table>
    
<div class="text-right mt-20">
    <!--<a callback="" data-method="POST" href="{{ action('Admin\SendingDomainController@verify', $server->uid) }}" class="btn btn-primary ajax_link">{{ trans('messages.sending_domain_verify') }}</a>-->
    <a onclick="tableRefreshAll();" class="btn btn-primary">{{ trans('messages.sending_domain_verify') }}</a>
</div>