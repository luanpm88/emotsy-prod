@if ($subscriptions->count() > 0)
	<table class="table table-box pml-table table-log"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($subscriptions as $key => $subscription)
			<tr>
				<td width="1%">
					@if ($subscription->ended())
						<i class="icon-dash bg-grey subscription-status-icon"></i>
					@elseif ($subscription->isPending() || $subscription->isNew())
						<i class="icon-sync bg-warning subscription-status-icon"></i>
					@else
						<i class="icon-checkmark bg-success subscription-status-icon"></i>
					@endif
				</td>
				<td>
					<h5 class="no-margin text-bold">
						<span class="kq_search" href="#">
							{{ $subscription->plan->name }}
						</span>
					</h5>
					<div class="text-muted">{{ trans('messages.subscribed_by', ['name' => $subscription->user->displayName()]) }}</div>
				</td>
				<td width="15%">
                    <h5 class="no-margin">
                        <span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->created_at) }}</span>
                    </h5>
                    <span class="text-muted2">{{ trans('messages.subscribed_on') }}</span>
                </td>
				<td width="15%">
					@if ($subscription->ended())
						<h5 class="no-margin">
							<span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->ends_at) }}</span>
						</h5>
						<span class="text-muted2">{{ trans('messages.subscription.ended_on') }}</span>
					@elseif ($subscription->cancelled())
						<h5 class="no-margin">
							<span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->ends_at) }}</span>
						</h5>
						<span class="text-muted2">{{ trans('messages.subscription.ends_on') }}</span>
					@else
						<h5 class="no-margin">
							<span class="kq_search">{{ Acelle\Library\Tool::formatDate($subscription->updated_at) }}</span>
						</h5>
						<span class="text-muted2">{{ trans('messages.subscription.updated_at') }}</span>
					@endif
				</td>
				<td>
                    <span class="text-muted2 list-status pull-left">
						@if (!$subscription->active())
							@if ($subscription->ended())
								<span class="label label-flat bg-cancelled">{{ trans('messages.subscription.status.ended') }}</span>
							@elseif ($subscription->isNew())
								<span class="label label-flat bg-new">{{ trans('messages.subscription.status.new') }}</span>
							@elseif ($subscription->isPending())
								<span class="label label-flat bg-pending">{{ trans('messages.subscription.status.pending') }}</span>
							@endif
						@else
							@if ($subscription->checkPendingPaymentForFuture($gateway))
								<span class="label label-flat bg-warning">{{ trans('messages.invoice.status.pending') }}</span>
							@endif
							@if (!$subscription->recurring())
								<span class="label label-flat bg-active">{{ trans('messages.subscription.status.active') }}</span>
							@endif
							@if ($subscription->ended())
								<span class="label label-flat bg-ended">{{ trans('messages.subscription.status.ended') }}</span>
							@endif
							@if ($subscription->cancelled() && !$subscription->ended())
								<!--<span class="label label-flat bg-cancelled">{{ trans('messages.subscription.status.cancelled') }}</span>-->
							@endif
							@if ($subscription->recurring())
								<span class="label label-flat bg-info">{{ trans('messages.subscription.status.recurring') }}</span>
							@endif
						@endif
					</span>
                </td>
				<td class="text-right">
					@if (\Auth::user()->admin->can('cancel', $subscription))
						<a data-method="POST" link-confirm="{{ trans('messages.subscription.cancel.confirm') }}"
						  href="{{ action('Admin\SubscriptionController@cancel', $subscription->uid) }}" class="btn bg-grey-600">
							{{ trans('messages.subscription.cancel') }}
						</a>
					@endif
					@if (\Auth::user()->admin->can('resume', $subscription))
						<a data-method="POST" link-confirm="{{ trans('messages.subscription.resume.confirm') }}"
						  href="{{ action('Admin\SubscriptionController@resume', $subscription->uid) }}" class="btn bg-grey-600">
							{{ trans('messages.subscription.resume') }}
						</a>
					@endif
					@if(
						\Auth::user()->admin->can('cancelNow', $subscription) ||
						\Auth::user()->admin->can('changePlan', $subscription) ||
						\Auth::user()->admin->can('invoices', $subscription) ||
						\Auth::user()->admin->can('setDone', $subscription)
					)
						<div class="btn-group">
							<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret ml-0"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								@if (\Auth::user()->admin->can('approvePendingInvoice', $subscription))
									<li>
										<a data-method="POST" link-confirm="{{ trans('messages.subscription.approve_pending_invoice.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@approvePendingInvoice', $subscription->uid) }}">
											{{ trans('messages.subscription.approve_pending_invoice') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('setDone', $subscription))
									<li>
										<a data-method="POST" link-confirm="{{ trans('messages.subscription.set_done.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@setDone', $subscription->uid) }}">
											{{ trans('messages.subscription.approve_payment') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('invoices', $subscription))
									<li>
										<a class="modal_link"
										  href="{{ action('Admin\SubscriptionController@invoices', $subscription->uid) }}">
											{{ trans('messages.subscription.logs') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('cancelNow', $subscription))
									<li>
										<a data-method="POST" link-confirm="{{ trans('messages.subscription.cancel_now.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@cancelNow', $subscription->uid) }}">
											{{ trans('messages.subscription.cancel_now') }}
										</a>
									</li>
								@endif
								@if (\Auth::user()->admin->can('delete', $subscription))
									<li>
										<a data-method="DELETE" link-confirm="{{ trans('messages.subscription.delete.confirm') }}"
										  href="{{ action('Admin\SubscriptionController@delete', ['id' => $subscription->uid]) }}">
											{{ trans('messages.subscription.delete') }}
										</a>
									</li>
								@endif
							</ul>
						</div>
					@endif
                </td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $subscriptions])
	{{ $subscriptions->links() }}
@elseif (!empty(request()->keyword) || !empty(request()->filters))
	<div class="empty-list">
		<i class="icon-quill4"></i>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<i class="icon-quill4"></i>
		<span class="line-1">
			{{ trans('messages.subscription_empty_line_1_admin') }}
		</span>
	</div>
@endif
