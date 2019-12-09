<form id="trigger-select" action="{{ action("Automation2Controller@actionEdit", ['uid' => $automation->uid, 'type' => $type]) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <input type="hidden" name="type" value="{{ $type }}" />
    
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
</form>