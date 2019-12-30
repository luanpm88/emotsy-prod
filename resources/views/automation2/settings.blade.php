@include('automation2._info')
				
@include('automation2._tabs', ['tab' => 'settings'])
    
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
            
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'timezone',
                'value' => 'America/Paramaribo',
                'options' => Tool::getTimezoneSelectOptions(),
                'include_blank' => trans('messages.choose'),
                'rules' => $automation->rules()
            ])
        </div>
    </div>
    
    <button class="btn btn-secondary mt-20">{{ trans('messages.automation.settings.save') }}</button>            
</form>
    
<script>
    setAutomationName('{{ $automation->name }}');

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

    var $sel = $('[name=mail_list_uid]').on('change', function() {
        if ($sel.data('confirm') == 'false') {
            confirm = `{{ trans('messages.automation.change_list.confirm') }}`;

            var dialog = new Dialog('confirm', {
                message: confirm,
                ok: function(dialog) {
                    // store new value        
                    $sel.trigger('update');     
                },
                cancel: function(dialog) {
                    // reset
                    $sel.trigger('restore');
                },
                close: function(dialog) {
                    // reset
                    $sel.trigger('restore');
                },
            });
        }
    }).on('restore', function() {
        $(this).data('confirm', 'true');
        $(this).val($(this).data('currVal')).change();
        $(this).data('confirm', 'false');
    }).on('update', function() {
        $(this).data('currVal', $(this).val());
        $(this).data('confirm', 'false');
    }).trigger('update');
</script>