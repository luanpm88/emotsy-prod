<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="event" />
        <input type="hidden" name="options[field]" value="created_at" />
    
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.before_or_after'),
            'name' => 'options[delay]',
            'value' => $trigger->getOption('delay'),
            'help_class' => 'trigger',
            'options' => $automation->getDelayOptions(),
            'rules' => [],
        ])
        
        @include('helpers.form_control', [
            'type' => 'time2',
            'name' => 'options[at]',
            'label' => trans('messages.automation.at'),
            'value' => $trigger->getOption('at'),
            'rules' => [],
            'help_class' => 'trigger'
        ])
    </div>
</div>