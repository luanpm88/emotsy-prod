@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.subscribers'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection

@section('page_header')

			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("MailListController@index") }}">{{ trans('messages.lists') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold">{{ $list->name }}</span>
				</h1>
			</div>

@endsection

@section('content')
				
				@include("lists._menu")
				
				<h2 class="text-bold text-teal-800 mb-10"><i class="icon-users4"></i> {{ trans('messages.subscribers') }}</h2>
				<br />
				<form class="listing-form"
					data-url="{{ action('SubscriberController@listing', $list->uid) }}"
					per-page="{{ Mailixa\Model\MailList::$itemsPerPage }}"					
				>				
					<div class="row top-list-controls">
						<div class="col-md-10">
							@if ($subscribers->count() >= 0)					
								<div class="filter-box">
									<div class="btn-group list_actions hide">
										<button type="button" class="btn btn-xs btn-grey-600 dropdown-toggle" data-toggle="dropdown">
											{{ trans('messages.actions') }} <span class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-right">											
											<li>
												<a link-confirm="{{ trans('messages.subscribe_subscribers_confirm') }}" href="{{ action('SubscriberController@subscribe', $list->uid) }}">
													<i class="icon-enter"></i> {{ trans('messages.subscribe') }}
												</a>
											</li>
											<li>
												<a link-confirm="{{ trans('messages.unsubscribe_subscribers_confirm') }}" href="{{ action('SubscriberController@unsubscribe', $list->uid) }}">
													<i class="icon-exit"></i> {{ trans('messages.unsubscribe') }}
												</a>
											</li>
											<li>
												<a delete-confirm="{{ trans('messages.delete_subscribers_confirm') }}" href="{{ action('SubscriberController@delete', $list->uid) }}">
													<i class="icon-trash"></i> {{ trans('messages.delete') }}
												</a>
											</li>
										</ul>
									</div>
									<div class="checkbox inline check_all_list">
										<label>
											<input type="checkbox" class="styled check_all">
										</label>
									</div>
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort-order">											
											<option value="created_at">{{ trans('messages.created_at') }}</option>
											<option value="email">{{ trans('messages.email') }}</option>
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
						<div class="col-md-2 text-right">
							<a href="{{ action("SubscriberController@create", $list->uid) }}" type="button" class="btn bg-info-800">
								<i class="icon icon-plus2"></i> {{ trans('messages.create_subscriber') }}
							</a>
						</div>
					</div>
					
					<div class="pml-table-container">
						
						
						
					</div>
				</form>
@endsection
