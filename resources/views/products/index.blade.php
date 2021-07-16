@extends('layouts.core.frontend')

@section('title', trans('messages.products'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.products') }}</span>
		</h1>
	</div>
@endsection

@section('content')
    <form class="listing-form view-{{ request()->view ? request()->view : 'grid' }}"
        data-url="{{ action('ProductController@listing', ['view' => request()->view]) }}"
        per-page="{{ Acelle\Model\Product::$itemsPerPage }}"
    >
        <div class="row top-list-controls py-2">
            <div class="col-md-8">
                <div class="filter-box">
                    <div class="dropdown list_actions" style="display: none">
                        <button role="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                            {{ trans('messages.actions') }} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a list-delete-confirm="{{ action('MailListController@deleteConfirm') }}" href="{{ action('MailListController@delete') }}"><span class="material-icons-outlined">
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
                            <option value="name">{{ trans('messages.name') }}</option>
                        </select>
                        <button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                            <span class="material-icons-outlined desc">
sort
</span>
                        </button>
                    </span>
                    <span class="filter-group">
                        <span class="title text-semibold text-muted">{{ trans('messages.source') }}</span>
                        <select class="select" name="source_uid">
                            <option value="" class="active">{{ trans('messages.all_source') }}</option>
                            @foreach (Acelle\Model\Source::all() as $source)
                                <option {!! request()->source_uid == $source->uid ? 'selected' : '' !!} value="{{ $source->uid }}" class="active">{{ $source->getName() }}</option>
                            @endforeach
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
            <div class="col-md-4 text-end d-flex align-items-center">
                <div class="view-toggle d-flex ml-auto">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ action('ProductController@index') }}" class="btn btn-default view-toogle grid m-icon">
                            <span class="material-icons-outlined">
                                grid_view
                            </span>
                        </a>
                        <a href="{{ action('ProductController@index', ['view' => 'list']) }}" class="btn btn-default view-toogle list m-icon mr-3">
                            <span class="material-icons-outlined">
                                reorder
                            </span>
                        </a>
                    </div>
                </div>
                <a href="{{ action("SourceController@index") }}" role="button" class="btn btn-secondary m-icon">
                    <span class="material-icons-outlined">store</span> {{ trans('messages.stores_connections') }}
                </a>
            </div>
        </div>

        <div class="pml-table-container"></div>
    </form>
@endsection
