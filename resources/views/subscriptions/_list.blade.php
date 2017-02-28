@if ($subscriptions->count() > 0)
	<br/>
	<table class="table table-box pml-table table-log"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($subscriptions as $key => $subscription)
			<div class="panel panel-plan mr-30 {{ $subscription->beingUsed() ? 'box-shadow' : '' }}">
				<div class="panel-heading subscription-state subscription-state-{{ $subscription->getState() }}" style="background-color: {{ $subscription->plan_color }}; color: white">
					<h4 class="pull-left panel-title text-center">													
						{{ $subscription->plan_name }}
					</h4>
				</div>
				<div class="panel-body pt-0 subscription-state subscription-state-{{ $subscription->getState() }}">
					<ul class="mt-0 mb-0 top-border-none plans-intro">
						<li>
							@if ($subscription->beingUsed())
								{{ trans_choice('messages.days_remain', $subscription->daysRemainCount()) }}:
								<span class="text-bold text-warning">{{ $subscription->daysRemainCount() }}</span>
							@else
								{{ trans_choice('messages.days_remain', 0) }}:
								<span class="text-bold">--</span>
							@endif
						</li>
						<li>
							{{ trans('messages.start_at') }}: <span class="text-bold">
								{{ $subscription->start_at ? \Acelle\Library\Tool::dateTime($subscription->start_at)->format(trans('messages.date_format')) : '' }}
							</span>
						</li>
						<li>
							{{ trans('messages.end_at') }}: <span class="text-bold">
								{{ $subscription->end_at ? \Acelle\Library\Tool::dateTime($subscription->end_at)->format(trans('messages.date_format')) : '' }}
							</span>
						</li>
						<li>
							{{ trans('messages.status') }}: <span class="text-muted2 list-status">
								<span class="label label-flat bg-{{ $subscription->getState() }}">
									{{ trans('messages.subscription_state_' . $subscription->getState()) }}</span>
							</span>
						</li>
						<li>
							<h4 class="text-semibold text-teal-800 mb-0">Options</h4>
						</li>
						<li>
							{!! trans('messages.sending_quota_intro', ["value" => $subscription->displayQuota()]) !!}
						</li>
						<li>
							{!! trans('messages.max_lists_intro', ["value" => $subscription->displayMaxList()]) !!}
						</li>
						<li>
							{!! trans('messages.max_subscribers_intro', ["value" => $subscription->displayMaxSubscriber()]) !!}
						</li>
						<li>
							{!! trans('messages.max_campaigns_intro', ["value" => $subscription->displayMaxCampaign()]) !!}
						</li>
						<li>
							{!! trans('messages.max_size_upload_total_intro', ["value" => $subscription->displayMaxSizeUploadTotal()]) !!}
						</li>
						<li>
							{!! trans('messages.max_file_size_upload_intro', ["value" => $subscription->displayFileSizeUpload()]) !!}
						</li>					
					</ul>
				</div>
				<div class="panel-footer pt-10 pb-10 text-center">												
					@can('update', $subscription)
						<a href="{{ action('SubscriptionController@edit', $subscription->uid) }}" class="btn bg-grey-600 btn-icon">
							<i class="icon icon-pencil pr-0 mr-0"></i> {{ trans('messages.edit') }}
						</a>
					@endcan
					@can('delete', $subscription)		
						<a class="btn btn-danger" delete-confirm="{{ trans('messages.delete_subscriptions_confirm') }}" href="{{ action('SubscriptionController@delete', ['uids' => $subscription->uid]) }}">
							<i class="icon-trash"></i> {{ trans('messages.delete') }}
						</a>
					@endcan
				</div>
			</div>
		@endforeach
	@include('elements/_per_page_select')
	{{ $subscriptions->links() }}
@elseif (!empty(request()->keyword))
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