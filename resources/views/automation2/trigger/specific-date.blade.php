<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'date2',
        'class' => '',
        'label' => 'Date',
        'name' => 'date',
        'value' => '',
        'help_class' => 'trigger',
        'rules' => [],
    ])
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'at',
        'label' => 'At',
        'value' => '',
        'rules' => [],
        'help_class' => 'trigger'
    ])
</div>