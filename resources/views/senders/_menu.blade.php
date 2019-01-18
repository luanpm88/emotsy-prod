<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-tabs-top page-second-nav">
            <li rel0="SenderController">
                <a href="{{ action('SenderController@index') }}" class="level-1">
                <i class="icon-envelop2 mr-10"></i> {{ trans('messages.email_addresses') }}
                </a>
            </li>
            <li rel0="SendingDomainController">
                <a href="{{ action('SendingDomainController@index') }}" class="level-1">
                <i class="icon-earth mr-10"></i> {{ trans('messages.domains') }}
                </a>
            </li>            
        </ul>
    </div>
</div>
