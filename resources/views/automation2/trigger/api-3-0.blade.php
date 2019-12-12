<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'text',
        'class' => '',
        'label' => '',
        'name' => 'options[api]',
        'value' => isset($trigger) ? $trigger->options->api : '',
        'help_class' => 'trigger',
        'rules' => [],
    ])
</div>