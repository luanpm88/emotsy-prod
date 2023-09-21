@if ($campaigns->count() > 0)
	<table class="table table-box pml-table mt-2"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($campaigns as $key => $campaign)
			<tr class="position-relative">
				<td width="1%" class="list-check-col">
					<div class="text-nowrap">
						<div class="checkbox inline me-1">
							<label>
								<input type="checkbox" class="node styled"
									name="uids[]"
									value="{{ $campaign->uid }}"
								/>
							</label>
						</div>
					</div>
				</td>
				<td>
					<a class="kq_search fw-600 d-block list-title" href="{{ action('Admin\CampaignController@edit', $campaign->uid) }}">
						{{ $campaign->name }}
					</a>
					<span class="text-muted">{{ trans('messages.' . $campaign->type) }}</span>

					@if ($campaign->readCache('SubscriberCount'))
						<div>
							<span class="text-semibold" data-popup="tooltip" title="{{ $campaign->displayRecipients() }}">
								{{ number_with_delimiter($campaign->readCache('SubscriberCount')) }} {{ trans('messages.recipients') }}
							</span>
						</div>
					@endif

					@if ($campaign->status != 'new' && isset($campaign->run_at))
						<span class="text-muted2 d-block xtooltip" title="{{ $campaign->scheduleDiffForHumans() }}">{{ trans('messages.run_at') }}: <span class="material-symbols-rounded">alarm</span>
							 {{ isset($campaign->run_at) ? $campaign->customer->formatDateTime($campaign->run_at, 'datetime_full') : "" }}</span>
					@else
						<span class="text-muted2 d-block">{{ trans('messages.updated_at') }}: {{ $campaign->customer->formatDateTime($campaign->created_at, 'datetime_full') }}</span>
					@endif
				</td>
                <td>
                    <h5 class="m-0 text-bold">
						{{ $campaign->customer->getName() }}
					</h5>
					<span class="text-muted">{{ trans('messages.customer') }}</span>
				</td>
				@if ($campaign->status != 'new')
					<td class="stat-fix-size-sm">
						<div class="single-stat-box pull-left ml-4">
							<span class="no-margin text-primary stat-num">{{ $campaign->isSending() ? number_to_percentage($campaign->deliveredRate(true)) : number_to_percentage($campaign->readCache('DeliveredRate')) }}</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ $campaign->isSending() ? number_to_percentage($campaign->deliveredRate(true)) : number_to_percentage($campaign->readCache('DeliveredRate')) }}">
								</div>
							</div>
							<span class="text-semibold text-nowrap">{{ $campaign->isSending() ? number_with_delimiter($campaign->deliveredCount()) : number_with_delimiter($campaign->readCache('DeliveredCount', 0)) }} / {{ number_with_delimiter($campaign->readCache('SubscriberCount', 0))  }}</span>
							<br />
							<span class="text-muted">{{ trans('messages.sent') }}</span>
						</div>
					</td>
					<td class="stat-fix-size-sm">
						<div class="single-stat-box pull-left ml-4">
							<span class="no-margin text-primary stat-num">{{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ number_to_percentage($campaign->readCache('UniqOpenRate')) }}">
								</div>
							</div>
							<span class="text-muted">{{ trans('messages.open_rate') }}</span>
						</div>
					</td>
					<td class="stat-fix-size-sm">
						<div class="single-stat-box pull-left ml-4">
							<span class="no-margin text-primary stat-num">{{ number_to_percentage($campaign->readCache('ClickedRate')) }}</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ number_to_percentage($campaign->readCache('ClickedRate')) }}">
								</div>
							</div>
							<span class="text-muted">{{ trans('messages.click_rate') }}</span>
						</div>
					</td>
				@else
					<td></td>
					<td></td>
					<td></td>
				@endif
				<td width="15%" class="text-center">
					@if ($campaign->isRejected())
                        <span class="text-muted2 list-status pull-left xtooltip" title="{{ $campaign->reject_reason }}" data-popup='tooltip'>
                            <span class="label label-flat bg-{{ $campaign->status }}">{{ trans('messages.campaign_status_' . $campaign->status) }}</span>
                        </span>
                    @else
                        <span class="text-muted2 list-status pull-left" title='{{ $campaign->isError() ? $campaign->extractErrorMessage() : '' }}' data-popup='tooltip'>
                            <span class="label label-flat bg-{{ $campaign->status }}">{{ trans('messages.campaign_status_' . $campaign->status) }}</span>
                        </span>
                        <pre style="display:none">{{ $campaign->last_error }}</pre>
                    @endif
				</td>
				<td class="text-end text-nowrap pe-0">
					<div class="d-flex align-items-center text-nowrap justify-content-end">
                        @if (Auth::user()->admin->can('approve', $campaign))
							<a list-action="approve" href="{{ action('Admin\CampaignController@approvePreview', $campaign->uid) }}" role="button"
								class="btn btn-primary btn-icon ms-1">
                                <span class="material-symbols-rounded">task_alt</span>
                                {{ trans('messages.campaign.preview_approve') }}
                            </a>
						@endif
					</div>
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $campaigns])
    
    <script>
        $(function() {
            CampaignApproval.init();
        });

        var CampaignApproval = function() {
            var popup;
            var approveButtons;

            var handleApproveButton = function() {
                approveButtons.forEach(button => {
                    button.addEventListener("click", function(e) {
                        e.preventDefault();
                        var url = button.getAttribute("href");

                        load(url);
                    });
                });
            };

            var load = function(url) {
                popup.load(url);
            };

            return {
                init: function() {
                    popup = new Popup();
                    approveButtons = document.querySelectorAll('[list-action="approve"]');

                    //
                    handleApproveButton();
                },

                getPopup: function() {
                    return popup;
                },
            };
        }();
    </script>

	<script>
		var CampaignsList = {
			copyPopup: null,

			getCopyPopup: function() {
				if (this.copyPopup === null) {
					this.copyPopup = new Popup();
				}

				return this.copyPopup;
			}
		}

		var CampaignsResendPopup = {
            popup: null,

            load: function(url) {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: url
                    });
                }
                this.popup.load({
					url: url
				});
            }
        }

		var CampaignsSendTestEmailPopup = {
            popup: null,

            load: function(url) {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: url
                    });
                }
                this.popup.load({
					url: url
				});
            }
        }

		$('.resend-campaign').click(function(e) {
			e.preventDefault();

			var url = $(this).attr('href');

			CampaignsResendPopup.load(url);
		});

		$('.copy-campaign-button').on('click', function(e) {
			e.preventDefault();			
			var url = $(this).attr('href');

			CampaignsList.getCopyPopup().load({
				url: url
			});
		});

		$('.send-a-test-email-link').on('click', function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			
			CampaignsSendTestEmailPopup.load(url);
		});

        $('[list-control="reject"]').click(function(e) {
            e.preventDefault();

            var src = $(this).attr('href');
            
            rejectPendingSub = new Popup();
            rejectPendingSub.load(src);
        });
	</script>
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-symbols-rounded">auto_awesome</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-symbols-rounded">auto_awesome</span>
		<span class="line-1">
			{{ trans('messages.campaign_empty_line_1') }}
		</span>
	</div>
@endif
