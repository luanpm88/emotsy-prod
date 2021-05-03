@include('automation2._back')

<h3 class="mb-3">{{ trans('messages.automation.operation.' . $element->getOption('operation_type')) }}</h3>
<p>{!! trans('messages.automation.operation.' .$element->getOption('operation_type'). '.desc', [
    'list_name' => $element->getOption('list_uid') ? \Acelle\Model\MailList::findByUid($element->getOption('list_uid'))->name : '',
]) !!}</p>

@if (request()->operation == 'update')
    <table class="table my-3 table-bordered">
        <thead class="thead-light">
            <tr>
                <th width="40%" scope="col">{{ trans('messages.field') }}</th>
                <th scope="col">{{ trans('messages.value') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($element->getOption('update') as $update)
                <tr>
                    <th scope="row">{{ \Acelle\Model\Field::findByUid($update->field_uid)->label }}</th>
                    <td>{{ $update->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@php
    $operation = request()->operation;
    if (in_array(request()->operation, ['copy','move','delete'])) {
        $operation = 'copy';
    }
@endphp

<a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@operationEdit', [
    'uid' => $automation->uid,
    'operation' =>  $operation . '_contact',
    'id' => request()->id,
]) }}')" class="btn btn-secondary">{{ trans('messages.automation.edit_operation') }}</a>

<div class="alert alert-warning mt-5 d-flex py-3">
    <div class="mr-3 mt-1">
        <span class="material-icons-outlined text-warning">
            warning_amber
        </span>
    </div>
    <div>
        <h6 class="mb-1">
            {{ trans('messages.automation.dangerous_zone') }}
        </h6>
        <p class="">
            {{ trans('messages.automation.action.delete.wording') }}                
        </p>
        <div class="mt-3">
            <a href="javascript:;" data-confirm="{{ trans('messages.automation.action.delete.confirm') }}"
                class="btn btn-secondary operation-delete">
                <i class='lnr lnr-trash mr-0'></i> {{ trans('messages.automation.delete_this_action') }}
            </a>
        </div>
    </div>
</div>

<script>

    $('.operation-delete').on('click', function(e) {
        e.preventDefault();
        
        var confirm = $(this).attr('data-confirm');
        var dialog = new Dialog('confirm', {
            message: confirm,
            ok: function(dialog) {
                // remove current node
                tree.getSelected().remove();
                
                // save tree
                saveData(function() {
                    sidebar.load();
                    // notify
                    notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.automation.action.deteled') }}');
                    
                    // load default sidebar
                    sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}');
                });
            },
        });
    });
</script>
