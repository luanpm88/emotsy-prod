<h2 class="mb-20">
    {{ trans('messages.automation.action.set_up_your_condition') }}
</h2>
<p class="mb-10">
    {{ trans('messages.automation.action.condition.intro') }}
</p>

<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Select criterion',
        'name' => 'criterion',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [
            ['text' => 'Subscriber read an Email', 'value' => 'read_email'],
            ['text' => 'Subscriber clicks on a Link', 'value' => 'click_link']
        ],
        'rules' => [],
    ])
</div>
    
<div class="mb-20" data-condition="read_email" style="display:none">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Which email subscriber reads',
        'name' => 'after',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [
            ['text' => 'Thank for joining our community', 'value' => '123'],
        ],
        'rules' => [],
    ])
</div>
    
<div class="mb-20" data-condition="click_link" style="display:none">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Which Link subscriber clicks',
        'name' => 'after',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [
            ['text' => 'Special discount from Topica', 'value' => '123'],
        ],
        'rules' => [],
    ])
</div>
    
<script>
    function toggleCriterion() {
        var value = $('[name=criterion]').val();
        
        $('[data-condition]').hide();
        $('[data-condition='+value+']').show();
    }

    // Toggle condition options
    $(document).on('change', '[name=criterion]', function() {
        toggleCriterion();
    });
    
    toggleCriterion();
</script>