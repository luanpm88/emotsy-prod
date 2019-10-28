<h2 class="mb-20">
    {{ trans('messages.automation.action.send-an-email') }}
</h2>
<p class="mb-10">
    {{ trans('messages.automation.action.send-an-email.intro') }}
</p>

<div class="mb-20">
    @include('helpers.form_control', [
        'type' => 'select',
        'class' => '',
        'label' => 'Select criterion',
        'name' => 'after',
        'value' => '',
        'help_class' => 'trigger',
        'options' => [
            ['text' => 'Subscriber read an Email', 'value' => 'read_email'],
            ['text' => 'Subscriber clicks on a Link', 'value' => 'click_link']
        ],
        'rules' => [],
    ])
</div>