<div class="modal-dialog">
    <div class="modal-content">
        <form id="copyCampaginForm"
            action="{{ action('CampaignController@copy', ['copy_campaign_uid' => $campaign->uid]) }}"
            method="POST">
            {{ csrf_field() }}

            <div class="modal-header">
                <h5 class="modal-title">{!! trans('messages.copy_campaign', [
                    'name' => $campaign->name
                ]) !!}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <p class="mb-2">{{ trans('messages.what_would_you_like_to_name_your_campaign') }}</p>

                @include('helpers.form_control', [
                    'type' => 'text',
                    'label' => '',
                    'name' => 'name',
                    'value' => request()->has('name') ? request()->name : trans("messages.copy_of_campaign", ['name' => $campaign->name]),
                    'help_class' => 'campaign',
                    'rules' => ['name' => 'required']
                ])
            </div>
            <div class="modal-footer">
                <button type="submit" id="doCopyButton" class="btn btn-primary">{{ trans('messages.copy') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('messages.close') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    var CampaignsCopy = {
        copy: function(url, data) {
            CampaignsList.getCopyPopup().mask();
            addButtonMask($('#doCopyButton'));

            // copy
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                globalError: false
            }).done(function(response) {
                notify({
                    type: 'success',
                    message: response,
                });

                CampaignsList.getCopyPopup().hide();
                CampaignsIndex.getList().load();

            }).fail(function(jqXHR, textStatus, errorThrown){
                // for debugging
                CampaignsList.getCopyPopup().loadHtml(jqXHR.responseText);
            }).always(function() {
                CampaignsList.getCopyPopup().unmask();
                removeButtonMask($('#doCopyButton'));
            });
        }
    }

    $(document).ready(function() {
        $('#copyCampaginForm').on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();

            CampaignsCopy.copy(url, data);
        });
    });
</script>
