@include('helpers.form_control', [
    'type' => 'select',
    'options' => $planGroup->getSendingLimitSelectOptions(),
    'class' => '',
    'name' => 'plan[options][sending_limit]',
    'label' => trans('messages.plan_group.set_a_limit'),
    'value' => $planGroup->getOption('sending_limit'),
    'help_class' => 'plan',
    'rules' => [],    
])

<input type="hidden" name="plan[options][sending_quota]" value="{{ $planGroup->getOption('sending_quota') }}" />
<input type="hidden" name="plan[options][sending_quota_time]" value="{{ $planGroup->getOption('sending_quota_time') }}" />
<input type="hidden" name="plan[options][sending_quota_time_unit]" value="{{ $planGroup->getOption('sending_quota_time_unit') }}" />

<script>
    $(function() {
        $('[name="plan[options][sending_limit]"]').on('change', function() {
            var val = $(this).val();

            if (val == 'custom') {
                PlansSecurity.getSendingLimitPopup().load();
            }
        });
    });
</script>