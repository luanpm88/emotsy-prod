@extends('layouts.backend')

@section('title', trans('messages.currencies'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.currencies') }}</span>
				</h1>
			</div>

@endsection

@section('content')

				<form class="listing-form"
					sort-url="{{ action('Admin\CurrencyController@sort') }}"
					data-url="{{ action('Admin\CurrencyController@listing') }}"
					per-page="{{ Acelle\Model\Admin::$itemsPerPage }}"
				>
					<div class="row top-list-controls py-2">
						<div class="col-md-10">
							@if ($currencies->count() >= 0)
								<div class="filter-box">
									<div class="btn-group list_actions hide mr-10">
										<button role="button" class="btn btn-xs btn-grey-600 dropdown-toggle" data-toggle="dropdown">
											{{ trans('messages.actions') }} <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a link-confirm="{{ trans('messages.enable_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@enable') }}"><i class="icon-checkbox-checked2"></i> {{ trans('messages.enable') }}</a></li>
											<li><a link-confirm="{{ trans('messages.disable_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@disable') }}"><i class="icon-checkbox-unchecked2"></i> {{ trans('messages.disable') }}</a></li>
											<li>
												<a delete-confirm="{{ trans('messages.delete_currencies_confirm') }}" href="{{ action('Admin\CurrencyController@delete') }}">
													<span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}
												</a>
											</li>
										</ul>
									</div>
									<div class="checkbox inline check_all_list">
										<label>
											<input type="checkbox" name="page_checked" class="styled check_all">
										</label>
									</div>
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort_order">
                                            <option value="currencies.created_at">{{ trans('messages.created_at') }}</option>
											<option value="currencies.name">{{ trans('messages.name') }}</option>
											<option value="currencies.code">{{ trans('messages.code') }}</option>
										</select>
										<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
											<span class="material-icons-outlined desc">
sort
</span>
										</button>
									</span>
									<span class="text-nowrap">
										<input type="text" name="keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
										<span class="material-icons-round">
search
</span>
									</span>
								</div>
							@endif
						</div>
						@can('create', new Acelle\Model\Currency())
							<div class="col-md-2 text-end">
								<a href="{{ action("Admin\CurrencyController@create") }}" role="button" class="btn btn-secondary">
									<span class="material-icons-round">
add
</span> {{ trans('messages.create_currency') }}
								</a>
							</div>
						@endcan
					</div>

					<div class="pml-table-container">



					</div>
				</form>
@endsection
