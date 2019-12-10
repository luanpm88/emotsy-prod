<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'date2',
        'class' => '',
        'label' => 'Date',
        'name' => 'options[date]',
        'value' => '',
        'help_class' => 'trigger',
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