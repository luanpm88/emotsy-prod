@include('helpers.form_control', [
    'type' => 'select',
    'options' => $plan->getSendingLimitSelectOptions(),
    'class' => '',
    'name' => 'options[sending_limit]',
    'label' => trans('messages.plan.sending_limit'),
    'value' => $plan->getOption('sending_limit'),
    'help_class' => 'plan',
    'rules' => [],    
])

<input type="hidden" name="options[sending_quota]" value="{{ $options['sending_quota'] }}" />
<input type="hidden" name="options[sending_quota_time]" value="{{ $options['sending_quota_time'] }}" />
<input type="hidden" name="options[sending_quota_time_unit]" value="{{ $options['sending_quota_time_unit'] }}" />