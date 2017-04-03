@if ($customers->count() > 0)
	<table class="table table-box pml-table"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($customers as $key => $item)
			<tr>
				<td width="1%">
					<img width="80" class="img-circle mr-10" src="{{ action('CustomerController@avatar', $item->uid) }}" alt="">
				</td>
				<td>
					<h5 class="no-margin text-bold">
						<a class="kq_search" href="{{ action('Reseller\CustomerController@edit', $item->uid) }}">{{ $item->displayName() }}</a>
					</h5>
					<span class="text-muted kq_search">{{ $item->user->email }}</span>
					<br />
					{{ $item->customerGroup->name }}
					<br />
					<span class="text-muted2">{{ trans('messages.created_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
				</td>
				<td class="stat-fix-size">
					<div class="single-stat-box pull-left ml-20">
						<span class="no-margin text-teal-800 stat-num">{{ $item->displaySendingQuotaUsage() }}</span>
						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-info" style="width: {{ $item->getSendingQuotaUsage() }}%">
							</div>
						</div>
						<span class="text-muted"><strong>{{ $item->getSendingQuotaUsage() }}/{{ $item->maxQuota() }}</strong><br /> {{ trans('messages.sending_quota') }}</span>
					</div>
					<div class="single-stat-box pull-left ml-20">
						<span class="no-margin text-teal-800 stat-num">{{ $item->displaySubscribersUsage() }}</span>
						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-info" style="width: {{ $item->subscribersUsage() }}%">
							</div>
						</div>
						<span class="text-muted"><strong>{{ $item->subscribers()->count() }}/{{ $item->maxSubscribers() }}</strong><br /> {{ trans('messages.subscribers') }}</span>
					</div>
					<div class="single-stat-box pull-left ml-20">
						<span class="no-margin text-teal-800 stat-num">{{ $item->displayListsUsage() }}</span>
						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-info" style="width: {{ $item->listsUsage() }}%">
							</div>
						</div>
						<span class="text-muted"><strong>{{ $item->lists()->count() }}/{{ $item->maxLists() }}</strong><br /> {{ trans('messages.lists') }}</span>
					</div>
					<div class="single-stat-box pull-left ml-20">
						<span class="no-margin text-teal-800 stat-num">{{ $item->displayCampaignsUsage() }}</span>
						<div class="progress progress-xxs">
							<div class="progress-bar progress-bar-info" style="width: {{ $item->campaignsUsage() }}%">
							</div>
						</div>
						<span class="text-muted"><strong>{{ $item->campaigns()->count() }}/{{ $item->maxCampaigns() }}</strong><br /> {{ trans('messages.campaigns') }}</span>
					</div>
				</td>
				<td>
					<span class="text-muted2 list-status pull-left">
						<span class="label label-flat bg-{{ $item->status }}">{{ $item->status }}</span>
					</span>
				</td>
				<td class="text-right">
					@can('loginAs', $item)
						<a href="{{ action('Reseller\CustomerController@loginAs', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.login_as_this_customer') }}" type="button" class="btn bg-teal-600 btn-icon"><i class="glyphicon glyphicon-random pr-5"></i></a>
					@endcan
					@can('update', $item)
						<a href="{{ action('Reseller\CustomerController@edit', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" type="button" class="btn bg-grey-600 btn-icon"><i class="icon icon-pencil pr-0 mr-0"></i></a>
					@endcan
					@if (Auth::user()->can('delete', $item) || Auth::user()->can('enable', $item) || Auth::user()->can('disable', $item) || Auth::user()->can('delete', $item))
						<div class="btn-group">
							<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret ml-0"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								@can('enable', $item)
									<li>
										<a link-confirm="{{ trans('messages.enable_customers_confirm') }}" href="{{ action('Reseller\CustomerController@enable', ["uids" => $item->uid]) }}">
											<i class="icon-checkbox-checked2"></i> {{ trans('messages.enable') }}
										</a>
									</li>
								@endcan
								@can('disable', $item)
									<li>
										<a link-confirm="{{ trans('messages.disable_customers_confirm') }}" href="{{ action('Reseller\CustomerController@disable', ["uids" => $item->uid]) }}">
											<i class="icon-checkbox-unchecked2"></i> {{ trans('messages.disable') }}
										</a>
									</li>
								@endcan
								<li>
									<a delete-confirm="{{ trans('messages.delete_users_confirm') }}" href="{{ action('Reseller\CustomerController@delete', ['uids' => $item->uid]) }}">
										<i class="icon-trash"></i> {{ trans('messages.delete') }}
									</a>
								</li>
							</ul>
						</div>
					@endcan
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select')
	{{ $customers->links() }}
@elseif (!empty(request()->filters))
	<div class="empty-list">
		<i class="icon-users"></i>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<i class="icon-users"></i>
		<span class="line-1">
			{{ trans('messages.customer_empty_line_1') }}
		</span>
	</div>
@endif
