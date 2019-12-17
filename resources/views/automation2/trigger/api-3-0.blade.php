<div class="mb-20">
    <input type="hidden" name="options[type]" value="api" />
    
    @include('helpers.form_control', [
        'type' => 'text',
        'class' => '',
        'label' => '',
        'name' => 'options[endpoint]',
        'value' => $trigger->getOption('endpoint'),
        'help_class' => 'trigger',
        'rules' => [],
    ])
</div>