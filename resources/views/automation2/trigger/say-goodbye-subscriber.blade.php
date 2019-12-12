<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'After',
        'name' => 'options[after]',
        'value' => isset($trigger) ? $trigger->options->after : '',
        'help_class' => 'trigger',
        'options' => [['text' => '5 minutes', 'value' => '+5_minutes']],
        'rules' => [],
    ])
</div>