<div class="modal-dialog">
    <div class="modal-content">
        <form id="copyListForm"
            action="{{ action('MailListController@copy', ['copy_list_uid' => $list->uid]) }}"
            method="POST">
            {{ csrf_field() }}

            <div class="modal-header">
                <h5 class="modal-title">{!! trans('messages.copy_list', [
                    'name' => $list->name
                ]) !!}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <p class="mb-2">{{ trans('messages.what_would_you_like_to_name_your_list') }}</p>

                @include('helpers.form_control', [
                    'type' => 'text',
                    'label' => '',
                    'name' => 'name',
                    'value' => trans("messages.copy_of_list", ['name' => $list->name]),
                    'help_class' => 'list',
                    'rules' => ['name' => 'required']
                ])
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ trans('messages.copy') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('messages.close') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    var ListsCopy = {
        copy: function(url, data) {
            // copy
            $.ajax({
                url: url,
                type: 'POST',
                data: data
            }).done(function(response) {
                notify({
                    type: 'success',
                    message: response,
                });

                ListsList.copyPopup.hide();
                ListsIndex.list.load();

            }).fail(function(jqXHR, textStatus, errorThrown){
                // for debugging
                ListsList.copyPopup.loadHtml(jqXHR.responseText);
            });
        }
    }

    $('#copyListForm').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        ListsCopy.copy(url, data);
    });
</script>
