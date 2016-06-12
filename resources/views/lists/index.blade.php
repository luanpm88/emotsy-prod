@extends('layouts.frontend')

@section('title', trans('messages.my_lists'))

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
					<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.my_lists') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
				<form class="listing-form">				
					<div class="row top-list-controls">
						<div class="col-md-10">
							@if ($lists->count() >= 0)					
								<div class="filter-box">
									<div class="checkbox inline">
										<label>
											<input type="checkbox" class="styled">
										</label>
									</div>
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select">
											<option value="custom_order" class="active">{{ trans('custom_order') }}</option>
											<option value="name">{{ trans('name') }}</option>
											<option value="created_at">{{ trans('created_at') }}</option>
										</select>
										<!--<div class="btn-group dropdown">
											<button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">{{ trans('custom_order') }} &nbsp;<span class="caret"></span></button>
											<ul class="dropdown-menu dropdown-menu-right form-sort">
												<li rel="custom_order" class="active"><a href="#">{{ trans('custom_order') }}</a></li>
												<li rel="name"><a href="#">{{ trans('name') }}</a></li>
												<li rel="created_at"><a href="#">{{ trans('created_at') }}</a></li>
											</ul>
										</div>-->
										<button data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs"><i class="icon-sort-amount-asc"></i></button>						
									</span>
									<input class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
								</div>
							@endif
						</div>
						<div class="col-md-2 text-right">
							<a href="{{ action("MailListController@create") }}" type="button" class="btn bg-info-800">
								<i class="icon icon-plus2"></i> {{ trans('messages.create_list') }}
							</a>
						</div>
					</div>
					
					<div class="">
						@if ($lists->count() > 0)
							<table class="table table-box pml-table"
								sort-url="{{ action('MailListController@sort') }}"
								sort-by="custom_order"
								sort-direction="desc"
								current-page="{{ !empty(app('request')->input('page')) ? app('request')->input('page') : 1 }}"
								per-page="{{ \Mailixa\Model\MailList::$itemsPerPage }}"
							>
								@foreach ($lists as $key => $item)
									<tr>
										<td width="1%">
											<div class="text-nowrap">
												<div class="checkbox inline">
													<label>
														<input type="checkbox" class="node styled"
																custom-order="{{ $item->custom_order }}"
																item-id="{{ $item->id }}"
														/>
													</label>
												</div>
												<i data-action="move" class="icon icon-more2 list-drag-button"></i>										
											</div>
										</td>
										<td>
											<h5 class="no-margin text-bold"><a href="#">{{ $item->name }}</a></h5>
											<span class="text-muted">{{ trans('messages.created_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
										</td>
										<td>
											<div class="single-stat-box pull-left">
												<span class="no-margin stat-num">{{ rand(0, 1000) }}</span>
												<br />
												<span class="text-muted">Subscribers</span>
											</div>
											<div class="single-stat-box pull-left">
												<span class="no-margin stat-num">{{ rand(0, 100) }}.0%</span>
												<br />
												<span class="text-muted">Opens</span>
											</div>
											<div class="single-stat-box pull-left">
												<span class="no-margin stat-num">{{ rand(0, 100) }}.0%</span>
												<br />
												<span class="text-muted">Clicks</span>
											</div>
											<br style="clear:both" />
										</td>
										<td class="text-right">
											<span class="text-muted2 list-status pull-left">
												<span class="label label-flat border-success-600 text-success-600">active</span>
											</span>
											<button data-popup="tooltip" title="Add subscriber" type="button" class="btn bg-teal btn-icon"><i class="icon icon-plus2"></i><i class="glyphicon glyphicon-user"></i></button>
											<button data-popup="tooltip" title="Overview" type="button" class="btn bg-grey btn-icon"><i class="icon-stats-growth"></i> <i class="icon-search4"></i></button>
											<div class="btn-group">										
												<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li><a href="#">Manage subscribers</a></li>
													<li><a href="#">Signup forms</a></li>
													<li><a href="#">Settings</a></li>
													<li><a href="#">Import</a></li>
													<li><a href="#">Exports</a></li>
													<li><a href="#">Duplicate list</a></li>
													<li><a href="#">Combine lists</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
							</table>
							{{ $lists->links() }}
						@else					
							<div class="empty-list">
								<i class="icon-address-book2"></i>
								<span class="line-1">
									{{ trans('messages.list_empty_line_1') }}
								</span>
								<span class="line-2">
									{{ trans('messages.list_empty_line_2') }}
								</span>
							</div>
						@endif
					</div>
				</form>
@endsection
