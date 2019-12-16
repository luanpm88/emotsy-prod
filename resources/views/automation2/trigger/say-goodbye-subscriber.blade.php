<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="options[type]" value="list-unsubscription" />
        
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.automation.after'),
            'name' => 'options[delay]',
            'value' => $trigger->getOption('delay'),
            'help_class' => 'trigger',
            'options' => $automation->getDelayOptions(),
            'rules' => [],
        ])
    </div>
</div>