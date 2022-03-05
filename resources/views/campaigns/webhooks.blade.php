@extends('layouts.popup.medium')

@section('title')
    {{ trans('messages.webhooks') }}
@endsection

@section('content')
    <div class="d-flex">
        <p>{{ trans('messages.webhooks.wording') }}</p>
        <div class="ms-4">
            <a href="javascript:;" class="btn btn-secondary add_webhooks_but text-nowrap">
                <i class="material-icons-outlined">add</i>
                {{ trans('messages.webhooks.add') }}
            </a>
        </div>
    </div>

    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ trans('messages.webhook.type') }}</th>
                    <th>{{ trans('messages.webhook.description') }}</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>

    <script>
        var CampaignsWebhooks = {
            addPopup: null,
			getAddPopup: function() {
				if (this.addPopup == null) {
					this.addPopup = new Popup({
						url: '{{ action('CampaignController@webhooksAdd', [
							'uid' => $campaign->uid,
						]) }}'
					});
				}

				return this.addPopup;
			}
        };

        $(function() {
            $('.add_webhooks_but').on('click', function(e) {
				e.preventDefault();

				CampaignsWebhooks.getAddPopup().load();
			});
        })
    </script>
@endsection