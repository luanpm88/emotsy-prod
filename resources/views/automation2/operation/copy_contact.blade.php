<h3 class="mb-3">{{ trans('messages.automation.operation.' . request()->operation) }}</h3>
<p>{{ trans('messages.automation.operation.' .request()->operation. '.desc') }}</p>

<div class="row my-2">
    <div class="col-md-8">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.operation.choose_an_action'),
            'name' => 'options[operation_type]',
            'value' => isset($element) ? $element->getOption('operation_type') : [],
            'options' => [
                ['value' => 'move', 'text' => trans('messages.operation.action.move')],
                ['value' => 'copy', 'text' => trans('messages.operation.action.copy')],
                ['value' => 'delete', 'text' => trans('messages.operation.action.delete')],
            ],
            'rules' => ['options.operation_type' => 'required'],
        ])
    </div>
</div>

<div class="row my-2">
    <div class="col-md-8">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => trans('messages.operation.target_list'),
            'name' => 'options[list_uid]',
            'value' => isset($element) ? $element->getOption('list_uid') : [],
            'options' => \Acelle\Model\MailList::getAll()->get()->map(function($list) {
                return ['text' => $list->name, 'value' => $list->uid];
            }),
            'rules' => ['options.list_uid' => 'required'],
            'include_blank' => trans('messages.choose_a_list'),
        ])
    </div>
</div>

<script>
    customValidate($('#operation-edit'));

    $('.add-more-operation').on('click', function(e) {
        e.preventDefault();

        $('#operation-edit').valid();
    });
</script>