@extends('layouts.backend')

@section('title', trans('messages.bounce_handlers'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>        
@endsection

@section('page_header')
    <div class="row">
        <div class="col-md-10">
        	<div class="page-title">
	            <ul class="breadcrumb breadcrumb-caret position-right">
	                <li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
	            </ul>
	            <h1>
	                <span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.bounce_handlers') }}</span>
	            </h1>
	            <p>{{ trans('messages.bounce_handler.intro') }}</p>
	        </div>
        </div>
    </div>
@endsection

@section('content')
                
    <form class="listing-form"
        sort-url="{{ action('Admin\BounceHandlerController@sort') }}"
        data-url="{{ action('Admin\BounceHandlerController@listing') }}"
        per-page="{{ Acelle\Model\BounceHandler::$itemsPerPage }}"                    
    >                
        <div class="row top-list-controls py-2">
            <div class="col-md-9">
                @if ($items->count() >= 0)                    
                    <div class="filter-box">
                        <div class="dropdown list_actions" style="display: none">
                            <button role="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                {{ trans('messages.actions') }} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a delete-confirm="{{ trans('messages.delete_bounce_handlers_confirm') }}" href="{{ action('Admin\BounceHandlerController@delete') }}"><span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
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
                                <option value="bounce_handlers.updated_at">{{ trans('messages.updated_at') }}</option>
                                <option value="bounce_handlers.name">{{ trans('messages.name') }}</option>
                                <option value="bounce_handlers.created_at">{{ trans('messages.created_at') }}</option>
                            </select>                                        
                            <input type="hidden" name="sort_direction" value="desc" />
<button class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
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
            @can('create', new Acelle\Model\BounceHandler())
                <div class="col-md-3 text-end">
                    <a href="{{ action('Admin\BounceHandlerController@create') }}" role="button" class="btn btn-secondary">
                        <span class="material-icons-round">
add
</span> {{ trans('messages.create_bounce_handler') }}
                    </a>
                </div>
            @endcan
        </div>
        
        <div class="pml-table-container">
        </div>
    </form>
@endsection
