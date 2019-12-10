<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Before or After',
        'name' => 'options[before_after]',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [['text' => '1 day before', 'value' => '-1_day']],
        'rules' => [],
    ])
</div>