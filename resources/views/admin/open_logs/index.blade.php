@extends('layouts.backend')

@section('title', trans('messages.open_log'))

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
					<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.open_log') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
				
				<form class="listing-form"
					data-url="{{ action('Admin\OpenLogController@listing') }}"
					per-page="{{ Acelle\Model\OpenLog::$itemsPerPage }}"					
				>				
					<div class="row top-list-controls">
						<div class="col-md-9">
							@if ($items->count() >= 0)					
								<div class="filter-box">
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort_order">
                                            <option value="open_logs.created_at">{{ trans('messages.created_at') }}</option>
										</select>										
										<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
											<span class="material-icons-outlined desc">
sort
</span>
										</button>
									</span>
									<span class="ml-10">										
										<select data-placeholder="{{ trans('messages.all_campaigns') }}" class="select2-ajax" name="campaign_uid" data-url="{{ action('CampaignController@select2') }}">
											
										</select>									
									</span>
									<span class="text-nowrap">
										<input name="keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
										<span class="material-icons-round">
search
</span>
									</span>
								</div>
							@endif
						</div>
					</div>
					
					<div class="pml-table-container">
						
						
						
					</div>
				</form>				
@endsection
