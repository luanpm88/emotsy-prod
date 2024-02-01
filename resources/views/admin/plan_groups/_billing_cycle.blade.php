@include('helpers.form_control', [
    'type' => 'select',
    'options' => $planGroup->getBillingCycleSelectOptions(),
    'class' => '',
    'name' => 'plan[options][billing_cycle]',
    'label' => trans('messages.plan_group.billing_cycle'),
    'value' => $planGroup->getOption('billing_cycle'),
    'help_class' => 'plan',
    'rules' => [],    
])

<input type="hidden" name="plan[general][frequency_amount]" value="{{ $planGroup->frequency_amount }}" />
<input type="hidden" name="plan[general][frequency_unit]" value="{{ $planGroup->frequency_unit }}" />