@if ($plans->count() > 0)
	<div class="row boxes-list boxes-plans table-box pml-table"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($plans as $key => $plan)
			<div class="col-md-3">
			
				<table class="table table-box pml-table hide"
					current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
				></table>
				
				<input type="hidden" class="node styled"
					custom-order="{{ $plan->custom_order }}"
					name="ids[]"
					value="{{ $plan->uid }}"
				/>
				
				@include('admin/plans/_plan')
				
			</div>
		@endforeach
	</div>
	@include('elements/_per_page_select')
	{{ $plans->links() }}
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<i class="icon-clipboard2"></i>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else					
	<div class="empty-list">
		<i class="icon-clipboard2"></i>
		<span class="line-1">
			{{ trans('messages.plan_empty_line_1') }}
		</span>
	</div>
@endif