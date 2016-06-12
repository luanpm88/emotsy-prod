@extends('layouts.frontend')

@section('title', trans('messages.my_lists'))

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
									<span class="title text-semibold text-muted">{{ trans('sort_by') }}</span>
									<div class="btn-group dropdown">
										<button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">{{ trans('custom_order') }} &nbsp;<span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li class="active"><a href="#">{{ trans('custom_order') }}</a></li>
											<li><a href="#">{{ trans('name') }}</a></li>
											<li><a href="#">{{ trans('created_at') }}</a></li>
										</ul>
									</div>
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
						<table class="table table-box">
							@foreach ($lists as $key => $item)
								<tr>
									<td width="1%">
										<div class="text-nowrap">
											<div class="checkbox inline">
												<label>
													<input type="checkbox" class="styled">
												</label>
											</div>
											<i class="icon icon-more2 list-drag-button"></i>										
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
@endsection
