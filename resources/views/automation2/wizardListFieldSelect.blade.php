<p class="mb-0 mt-2">{{ trans('messages.automation.choose_birthday_field') }}:</p>
<div class="row">
    <div class="col-md-6 mt-2">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'include_blank' => trans('messages.automation.choose_list_field'),
            'name' => 'options[field]',
            'value' => '',
            'help_class' => 'trigger',
            'options' => $list->getFields()->get()->map(function($field) {
                return ['text' => $field->label, 'value' => $field->uid];
            })->toArray(),
        ])
    </div>
</div>