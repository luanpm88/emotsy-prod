@php
    $options = [
        ['text' => trans('messages.automation.condition.open'), 'value' => 'open'],
        ['text' => trans('messages.automation.condition.click'), 'value' => 'click'],
    ];

    $trigger = $automation->getTrigger();

    if ($trigger->getOption('type') == 'woo-abandoned-cart') {
        $options = array_merge($options, [
            ['text' => trans('messages.automation.condition.cart_buy_anything'), 'value' => 'cart_buy_anything'],
            ['text' => trans('messages.automation.condition.cart_buy_item'), 'value' => 'cart_buy_item'],
        ]);
    }
@endphp

<h5 class="mb-3">
    {{ trans('messages.automation.action.set_up_your_condition') }}
</h5>
<p class="mb-3">
    {{ trans('messages.automation.action.condition.intro') }}
</p>

<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Select criterion',
        'name' => 'type',
        'value' => $element->getOption('type'),
        'help_class' => 'trigger',
        'options' => $options,
        'rules' => [],
    ])
</div>
    
<div class="condition-setting">
</div>
    
<script>
    var settingBox = new Box($('.condition-setting'));

    function showSetting() {
        var value = $('[name=type]').val();
        var url = '{{ action('Automation2Controller@conditionSetting', [
        'uid' => $automation->uid,
        'element_id' => $element->get('id'),
    ]) }}&type=' + value;

        settingBox.load(url);
    }

    // Toggle condition options
    $(document).on('change', '[name=type]', function() {
        showSetting();
    });
    
    showSetting();
</script>