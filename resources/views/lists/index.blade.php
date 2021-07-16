@extends('layouts.core.frontend')

@section('title', trans('messages.my_lists'))

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
			<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.my_lists') }}</span>
		</h1>
	</div>

@endsection

@section('content')
    <div class="listing-form" id="ListsIndexContainer">
        <div class="row top-list-controls py-2">
            <div class="col-md-10">
                @if (Auth::user()->customer->listsCount() >= 0)
                    <div class="filter-box">
                        <div class="checkbox inline check_all_list">
                            <label>
                                <input type="checkbox" name="page_checked" class="styled check_all">
                            </label>
                        </div>
                        <div class="dropdown list_actions" style="display: none">
                            <button type="button"
                                class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown"
                            >
                                {{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a
                                        class="dropdown-item"
                                        link-confirm-url="{{ action('MailListController@deleteConfirm') }}"
                                        link-method="POST"
                                        href="{{ action('MailListController@delete') }}"
                                    >
                                        <span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}</a>
                                </li>
                            </ul>
                        </div>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="created_at">{{ trans('messages.created_at') }}</option>
                                <option value="name">{{ trans('messages.name') }}</option>
                            </select>

                            <input type="hidden" name="sort_direction" value="desc" />
                            <button class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-icons-outlined">
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
            <div class="col-md-2 text-end">
                <a href="{{ action("MailListController@create") }}" role="button" class="btn btn-secondary">
                    <span class="material-icons-round">
                        add
                    </span> {{ trans('messages.create_list') }}
                </a>
            </div>
        </div>

        <div id="ListsIndexContent"></div>
    </div>

    <script>
        var ListsIndex = {
            getList: function() {
                return makeList({
                    url: '{{ action('MailListController@listing') }}',
                    container: $('#ListsIndexContainer'),
                    content: $('#ListsIndexContent')
                });
            }
        };

        $(document).ready(function() {
            ListsIndex.getList().load();
        });
    </script>
@endsection
