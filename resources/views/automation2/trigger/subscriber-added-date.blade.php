<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Before or After',
        'name' => 'options[before_after]',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [['text' => '1 day before', 'value' => '-1day']],
        'rules' => [],
    ])
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'options[at]',
        'label' => 'At',
        'value' => '',
        'rules' => [],
        'help_class' => 'trigger'
    ])
</div>