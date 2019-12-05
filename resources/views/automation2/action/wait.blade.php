<h5 class="mb-20 mt-3">
    {{ trans('messages.automation.action.wait') }}
</h5>
<p class="mb-10">
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
        'options' => [['text' => '30 minutes', 'value' => '+30_minutes']],
        'rules' => [],
    ])
</div>