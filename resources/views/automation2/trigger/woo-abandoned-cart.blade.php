<div class="mb-20">
    <input type="hidden" name="options[type]" value="woo-abandoned-cart" />
    
    @include('helpers.form_control', [
        'type' => 'text',
        'class' => '',
        'label' => '',
        'name' => 'options[connect_url]',
        'value' => $trigger->getOption('connect_url'),
        'help_class' => 'trigger',
        'rules' => [],
    ])
</div>