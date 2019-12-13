<div class="sidebar-header flex-center mb-2">
    <h5 class="m-0 mr-auto">{{ $automation->name }}</h5>
    <span class="mr-2 small"><i>{{ trans('messages.automation.status.' . $automation->status) }}</i></span>
    <div>
        @include('helpers.form_control', [
            'type' => 'checkbox',
            'name' => 'automation_status',
            'label' => '',
            'class' => 'automation_status',
            'value' => ($automation->status == \Acelle\Model\Automation2::STATUS_ACTIVE ? true : false),
            'options' => [false,true],
            'help_class' => '',
            'rules' => []
        ])
    </div>
</div>
<div class="flex">
    <p class="pr-4">
        {!! trans('messages.automation.intro', ['list' => $automation->mailList->name]) !!}
    </p>
    <div>
        <a href="javascrip:;" class="btn btn-info" onclick="sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}')">
            <i class="lnr lnr-cog mr-2"></i>{{ trans('messages.automation.settings') }}
        </a>
    </div>         
</div>
    
<script>
    // change automation status
    $('[name="automation_status"]').change(function() {
        var value = $(this).is(":checked");
        var url;
        
        if (value) {
            url = '{{ action('Automation2Controller@enable', ["uids" => $automation->uid]) }}';
        } else {
            url = '{{ action('Automation2Controller@disable', ["uids" => $automation->uid]) }}';
        }
        
        $.ajax({
            url: url,
            type: 'PATCH',
            data: {
                _token: CSRF_TOKEN
            }
        }).always(function(response) {
            notify(response.status, '{{ trans('messages.notify.success') }}', response.message);
        
            sidebar.load();
        });        
    });
</script>