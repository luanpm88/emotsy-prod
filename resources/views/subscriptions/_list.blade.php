@if ($subscriptions->count() > 0)
	<br/>
	<table class="table table-box pml-table table-log"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($subscriptions as $key => $subscription)
			@include('subscriptions._subscription')
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