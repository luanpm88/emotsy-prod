@extends('layouts.backend')

@section('title', trans('messages.notifications'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')	
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
			<li class="active">{{ trans('messages.notifications') }}</li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="material-icons-outlined">message</i>  {{ trans('messages.all_notifications') }}</span>
		</h1>
	</div>				
@endsection

@section('content')
	
	@include("admin.account._menu")	

	<form class="listing-form"
		data-url="{{ action('Admin\NotificationController@listing') }}"
		per-page="20"				
	>				
		<div class="row top-list-controls py-2">
			<div class="col-md-10">			
				<div class="filter-box">
					<div class="dropdown list_actions" style="display: none">
						<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
							{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li>
								<a link-method="GET" href="{{ action('Admin\NotificationController@delete') }}" class="ajax_link"><span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}</a>
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
							<option value="created_at">{{ trans('messages.created_at') }}</option>
						</select>										
						<input type="hidden" name="sort_direction" value="desc" />
<button class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
							<span class="material-icons-outlined desc">
sort
</span>
						</button>
					</span>
					<span class="filter-group ml-10">
						<span class="title text-semibold text-muted">{{ trans('messages.notification.level') }}</span>
						<select class="select" name="level">
							<option value="">{{ trans('messages.all') }}</option>
							<option value="{{ \Acelle\Model\Notification::LEVEL_INFO }}">{{ trans('messages.notification.level.' . \Acelle\Model\Notification::LEVEL_INFO) }}</option>
							<option value="{{ \Acelle\Model\Notification::LEVEL_WARNING }}">{{ trans('messages.notification.level.' . \Acelle\Model\Notification::LEVEL_WARNING) }}</option>
							<option value="{{ \Acelle\Model\Notification::LEVEL_ERROR }}">{{ trans('messages.notification.level.' . \Acelle\Model\Notification::LEVEL_ERROR) }}</option>
						</select>										
					</span>
					<span class="text-nowrap">
						<input type="text" name="keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
						<span class="material-icons-round">
search
</span>
					</span>
				</div>
			</div>
		</div>
		
		<div class="pml-table-container">

		</div>
	</form>	
@endsection