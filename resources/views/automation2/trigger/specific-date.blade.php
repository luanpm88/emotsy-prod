<div class="mb-20">
    <input type="hidden" name="options[type]" value="datetime" />

    @php
        $customer = Auth::user()->customer;

        $date = $trigger->getOptionAsDateTime('date')->format('Y-m-d');
        if (!$date) {            
            $date = $customer->getCurrentTime()->format('Y-m-d');
        }

        $time = $trigger->getOptionAsDateTime('at')->format('h:i A');
        if (!$time) {
            $time = $customer->getCurrentTime()->format('h:i A');
        }
    @endphp

    @include('helpers.form_control', [
        'type' => 'date2',
        'class' => '',
        'label' => trans('messages.automation.date'),
        'name' => 'options[date]',
        'value' => $date,
        'help_class' => 'trigger',
        'rules' => $rules,
    ])
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'options[at]',
        'label' => trans('messages.automation.at'),
        'value' => $time,
        'rules' => $rules,
        'help_class' => 'trigger'
    ])
</div>