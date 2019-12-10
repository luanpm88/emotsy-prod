<h4 class="mb-3 mt-3">
    {{ trans('messages.automation.action.wait') }}
</h4>
<p class="mb-3">
    {{ trans('messages.automation.action.wait.intro') }}
</p>

<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => '',
        'name' => 'after',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [['text' => '30 minutes', 'value' => '+30_minutes'], ['text' => '1 hour', 'value' => '+1_hour']],
        'rules' => [],
    ])
</div>
