@include('automation2._info')
				
<div class="sidebar-setting-container">
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="lnr lnr-cog mr-2"></i>
                {{ trans('messages.automation.settings') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="lnr lnr-chart-bars mr-2"></i>
                {{ trans('messages.automation.insight') }}
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="lnr lnr-menu mr-2"></i> {{ trans('messages.automation.statistics') }}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Separated link</a>
            </div>
        </li>
    </ul>
        
    <p class="mt-3">
        {!! trans('messages.automation.settings.intro') !!}
    </p>
        
    <form id="automationUpdate" action="{{ action("Automation2Controller@update", $automation->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        
        <div class="row mb-3">
            <div class="col-md-8">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'label' => trans('messages.automation.automation_name'),
                    'name' => 'name',
                    'value' => $automation->name,
                    'help_class' => 'automation',
                    'rules' => $automation->rules(),
                ])
                
                @include('helpers.form_control', [
                    'name' => 'mail_list_uid',
                    'include_blank' => trans('messages.automation.choose_list'),
                    'type' => 'select',
                    'label' => trans('messages.automation.change_mail_list'),
                    'value' => (is_object($automation->mailList) ? $automation->mailList->uid : ''),
                    'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                    'rules' => $automation->rules(),
                ])
            </div>
        </div>
        
        <button class="btn btn-secondary mt-20">{{ trans('messages.automation.settings.save') }}</button>            
    </form>
        
    <!--<p class="mt-3">
        {{ trans('messages.automation.trigger.empty_msg') }}
    </p>
        
    <button class="btn btn-secondary add-trigger-but">
        {{ trans('messages.automation.trigger.setup') }}
    </button>-->
</div>
    
<script>
    $('#automationUpdate').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        
        // loading effect
        sidebar.loading();
        
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            statusCode: {
                // validate error
                400: function (res) {
                   sidebar.loadHtml(res.responseText);
                }
             },
             success: function (response) {
                sidebar.load();
                
                notify(response.status, '{{ trans('messages.notify.success') }}', response.message);
             }
        });
    });
</script>