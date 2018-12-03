  <div class="modal-dialog mc-modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('helpers.form_control', [
            'type' => 'autofill',
            'name' => 'from_email',
            'label' => trans('messages.from_email'),
            'value' => '',
            'rules' => [],
            'help_class' => 'campaign',
            'url' => action('SenderController@dropbox'),
            'empty' => trans('messages.sender.dropbox.empty'),
            'error' => trans('messages.sender.dropbox.error', [
                'sender_link' => action('SenderController@index'),
            ]),
            'header' => trans('messages.verified_senders'),
        ])
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-mc_primary">Save changes</button>
        <button type="button" class="btn btn-mc_inline" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>