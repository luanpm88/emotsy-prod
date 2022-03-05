@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.webhooks.add_webhook') }}
@endsection

@section('content')
    <form class="add-webhook-form" action="{{ action('CampaignController@webhooksAdd', [
        'uid' => $campaign->uid,
    ]) }}"
        method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-12">
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'type',
                    'value' => $webhook->type,
                    'label' => trans('messages.webhook.select_type'),
                    'options' => [
                        ['text' => trans('messages.webhook.type.open'), 'value' => 'open'],
                        ['text' => trans('messages.webhook.type.click'), 'value' => 'click'],
                        ['text' => trans('messages.webhook.type.unsubscribe'), 'value' => 'unsubscribe'],
                    ],
                    'rules' => ['type' => 'required'],
                ])

                <div class="link_select"></div>

                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'endpoint',
                    'value' => $webhook->endpoint,
                    'label' => trans('messages.webhook.endpoint.desc'),
                    'rules' => ['endpoint' => 'required'],
                ])
            </div>
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-secondary add-link-save">{{ trans('messages.save') }}</button>
        </div>
    </form>

    <script>
        var CampaignsWebhooksAdd = {
            getType: function() {
                return $('.add-webhook-form [name=type]').val();
            },
            
            showLinkSelect: function() {
                var box = new Box($('.link_select'), '{{ action('CampaignController@webhooksLinkSelect', [
                    'uid' => $campaign->uid,
                    'campaign_link_id' => $webhook->campaign_link_id,
                ]) }}');
                box.load();
            },

            hideLinkSelect: function() {
                $('.link_select').html('');
            },

            toggleLinkSelect: function() {
                if (this.getType() == 'click') {
                    this.showLinkSelect();
                } else {
                    this.hideLinkSelect();
                }
            },

            save: function() {
                addButtonMask($('.add-link-save'));
                var data = $('.add-webhook-form').serialize();
                var url = $('.add-webhook-form').attr('action');

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    notify({
                        type: 'success',
                        message: response.message,
                    });

                    CampaignsWebhooks.getAddPopup().hide();
                    CampaignsWebhooks.loadList();
                }).fail(function(jqXHR, textStatus, errorThrown){
                    CampaignsWebhooks.getAddPopup().loadHtml(jqXHR.responseText);
                }).always(function() {
                    removeButtonMask($('.add-link-save'));
                });
            }
        };

        $(function() {
            $('.add-webhook-form').on('submit', function(e) {
				e.preventDefault();

                CampaignsWebhooksAdd.save();
			});

            // toogle link select
            CampaignsWebhooksAdd.toggleLinkSelect();
            $('.add-webhook-form [name=type]').on('change', function(e) {
				CampaignsWebhooksAdd.toggleLinkSelect();
			});
        })
    </script>
@endsection