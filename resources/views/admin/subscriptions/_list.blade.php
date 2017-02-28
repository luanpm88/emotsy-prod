@if ($subscriptions->count() > 0)
	<table class="table table-box pml-table table-log"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($subscriptions as $key => $subscription)
			<tr>
				<td width="1%">
					<img width="80" class="img-circle mr-10" src="{{ action('CustomerController@avatar', $subscription->customer->uid) }}" alt="">
				</td>
				<td>
					<h5 class="no-margin text-bold">
						<a class="kq_search" href="{{ action('SubscriptionController@edit', $subscription->uid) }}">
							{{ $subscription->customer->displayName() }}
						</a>
					</h5>
					<span class="text-muted kq_search">{{ $subscription->customer->user->email }}</span>
					<br />
					<span><i class="icon-clipboard2"></i> {{ $subscription->plan->name }}</span>
				</td>
				<td>
					<h5 class="no-margin text-bold">
						{{ Acelle\Library\Tool::format_price($subscription->price, $subscription->currency_format) }}
					</h5>
					<span class="text-muted">{{ trans('messages.price') }}</span>
				</td>
				<td>
					<h5 class="no-margin text-bold">
						{{ Acelle\Library\Tool::formatDate($subscription->start_at) }}
					</h5>
					<span class="text-muted">{{ trans('messages.start_at') }}</span>
				</td>
				<td>
					<h5 class="no-margin text-bold">
						{{ Acelle\Library\Tool::formatDate($subscription->end_at) }}
					</h5>
					<span class="text-muted">{{ trans('messages.end_at') }}</span>
				</td>
				<td>
					@if ($subscription->beingUsed())
						<h5 class="no-margin text-bold">
							{{ $subscription->daysRemainCount() }}
						</h5>
						<span class="text-muted">{!! trans_choice('messages.days_remain', $subscription->daysRemainCount()) !!}</span>
					@else
						<h5 class="no-margin text-bold">
							--
						</h5>
						<span class="text-muted">{!! trans_choice('messages.days_remain', 0) !!}</span>
					@endif
				</td>
				<td>
					<span class="text-muted2 list-status pull-left">
						<span class="label label-flat bg-{{ $subscription->status }}">{{ trans('messages.subscription_status_' . $subscription->status) }}</span>
					</span>
				</td>
				<td>
					<span class="text-muted2 list-status pull-left">
						<span class="label label-flat bg-{{ $subscription->getState() }}">{{ trans('messages.subscription_state_' . $subscription->getState()) }}</span>
					</span>
				</td>
				<td class="text-right">											
					@can('update', $subscription)
						<a href="{{ action('Admin\SubscriptionController@edit', $subscription->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" type="button" class="btn bg-grey-600 btn-icon"><i class="icon icon-pencil pr-0 mr-0"></i></a>
					@endcan
					@if (Auth::user()->can('delete', $subscription) || Auth::user()->can('enable', $subscription) || Auth::user()->can('disable', $subscription))
						<div class="btn-group">										
							<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret ml-0"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								@can('enable', $subscription)
									<li>														
										<a data-method='PATCH' link-confirm="{{ trans('messages.enable_subscriptions_confirm') }}" href="{{ action('Admin\SubscriptionController@enable', ["uids" => $subscription->uid]) }}">
											<i class="icon-checkbox-checked2"></i> {{ trans('messages.enable') }}
										</a>
									</li>
								@endcan
								@can('disable', $subscription)
									<li>														
										<a data-method='PATCH' link-confirm="{{ trans('messages.disable_subscriptions_confirm') }}" href="{{ action('Admin\SubscriptionController@disable', ["uids" => $subscription->uid]) }}">
											<i class="icon-checkbox-unchecked2"></i> {{ trans('messages.disable') }}
										</a>
									</li>
								@endcan
								@can('delete', $subscription)
									<li>														
										<a delete-confirm="{{ trans('messages.delete_subscriptions_confirm') }}" href="{{ action('Admin\SubscriptionController@delete', ['uids' => $subscription->uid]) }}">
											<i class="icon-trash"></i> {{ trans('messages.delete') }}
										</a>
									</li>
								@endcan
							</ul>
						</div>
					@endcan
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select')
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
			{{ trans('messages.subscription_empty_line_1') }}
		</span>
	</div>
@endif