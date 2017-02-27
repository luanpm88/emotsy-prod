@extends('layouts.frontend')

@section('title', trans('messages.your_subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-quill4"></i> {{ trans('messages.your_subscriptions') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
	
	<h4 class="mt-0">{{ trans('messages.these_are_your_current_plans') }}</h4>
	
	<form class="listing-form"
		sort-url="{{ action('SubscriptionController@sort') }}"
		data-url="{{ action('SubscriptionController@listing') }}"
		per-page="{{ Acelle\Model\Subscription::$itemsPerPage }}"					
	>				
		<div class="row top-list-controls">
			<div class="col-md-10">
				@if ($subscriptions->count() >= 0)					
					<div class="filter-box">
						<span class="filter-group">
							<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
							<select class="select" name="sort-order">
								<option value="subscriptions.created_at">{{ trans('messages.created_at') }}</option>
								<option value="subscriptions.updated_at">{{ trans('messages.updated_at') }}</option>
							</select>										
							<button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
								<i class="icon-sort-amount-asc"></i>
							</button>
						</span>
						<span class="text-nowrap">
							<input name="search_keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
							<i class="icon-search4 keyword_search_button"></i>
						</span>
					</div>
				@endif
			</div>
			@can('create', new Acelle\Model\Subscription())
				<div class="col-md-2 text-right">
					<a href="{{ action("SubscriptionController@create") }}" type="button" class="btn bg-info-800">
						<i class="icon icon-plus2"></i> {{ trans('messages.subscribe_a_new_plan') }}
					</a>
				</div>
			@else
				<div class="col-md-2 text-right" data-popup="tooltip" title="{{ trans('messages.you_have_inactive_subscription') }}">
					<a href="{{ action("SubscriptionController@create") }}" type="button" class="btn btn-default disabled">
						<i class="icon icon-plus2"></i> {{ trans('messages.subscribe_a_new_plan') }}
					</a>
				</div>
			@endcan
		</div>
		
		<div class="pml-table-container">
			
			
			
		</div>
	</form>

@endsection
