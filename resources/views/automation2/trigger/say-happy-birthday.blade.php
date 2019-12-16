<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="event" />
        <input type="hidden" name="options[field]" value="date_of_birth" />
        
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.before_or_after'),
            'name' => 'options[delay]',
            'value' => $trigger->getOption('delay'),
            'help_class' => 'trigger',
            'options' => $automation->getDelayBeforeOptions(),
            'rules' => [],
        ])
    </div>
</div>