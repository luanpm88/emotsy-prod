<table class="table">
    <thead>
        <tr>
            <th>{{ trans('messages.webhook.type') }}</th>
            <th>{{ trans('messages.webhook.description') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($campaign->campaignWebhooks as $webhook)
            <tr>
                <td width="5%">{{ trans('messages.webhook.type.' . $webhook->type . '.short') }}</td>
                <td>
                    <p class="mb-0">{{ trans('messages.webhook.type.' . $webhook->type . '.desc') }}</p>
                    <p class="mb-0">
                        <span class="material-icons-outlined text-muted2 me-1 xtooltip" title="{{ trans('messages.webhook.endpoint') }}">gps_fixed</span> {{ $webhook->endpoint }}
                    </p>
                    @if ($webhook->type == 'click')
                        <p class="mb-0">
                            <span class="material-icons-outlined text-muted2 me-1 xtooltip" title="{{ trans('messages.link') }}">link</span>
                            <a target="_blank" href="{{ $webhook->campaignLink->url }}">{{ $webhook->campaignLink->url }}</a>
                        </p>
                    @endif
                </td>
                <td class="text-nowrap text-end">
                    <a href="" class="btn btn-light">
                        <i class="material-icons-outlined me-1">ios_share</i>
                        {{ trans('messages.webhook.sample_request') }}
                    </a>
                    <a href="" class="btn btn-light">
                        <i class="material-icons-outlined">bug_report</i>
                        {{ trans('messages.webhook.test') }}
                    </a>
                    <div class="btn-group">
                        <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item edit-webhook"
                                    href="{{ action('CampaignController@webhooksEdit', [
                                        'webhook_uid' => $webhook->uid,
                                    ]) }}">
                                    <i class="material-icons-outlined">edit</i>
                                    {{ trans('messages.edit') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item delete-webhook"
                                    href="{{ action('CampaignController@webhooksDelete', [
                                        'webhook_uid' => $webhook->uid,
                                    ]) }}">
                                    <i class="material-icons-outlined">delete</i>
                                    {{ trans('messages.delete') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    var CampaignsWebhooksList = {
        editPopup: null,
        getEditPopup: function(url) {
            if (this.editPopup == null) {
                this.editPopup = new Popup();
            }

            return this.editPopup;
        },

        delete: function(url) {
            new Dialog('confirm', {
                message: '{{ trans('messages.webhook.delete.confirm') }}',
                ok: function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: CSRF_TOKEN
                        },
                        success: function (response) {
                            notify({
                                type: 'success',
                                message: response.message
                            });

                            CampaignsWebhooks.loadList();
                        }
                    });
                }
            });
        }
    }

    $(function() {
        
        $('.edit-webhook').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            CampaignsWebhooksList.getEditPopup().load(url);
        });

        $('.delete-webhook').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            CampaignsWebhooksList.delete(url);
        });
    });
</script>