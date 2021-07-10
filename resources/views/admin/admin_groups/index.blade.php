@extends('layouts.backend')

@section('title', trans('messages.admin_groups'))

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
            <span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.admin_groups') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <form class="listing-form"
        sort-url="{{ action('Admin\AdminGroupController@sort') }}"
        data-url="{{ action('Admin\AdminGroupController@listing') }}"
        per-page="{{ Acelle\Model\AdminGroup::$itemsPerPage }}"
    >
        <div class="row top-list-controls">
            <div class="col-md-10">
                @if ($groups->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="admin_groups.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="admin_groups.name">{{ trans('messages.name') }}</option>
                            </select>
                            <button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-icons-outlined desc">
sort
</span>
                            </button>
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
            @can('create', new Acelle\Model\AdminGroup())
                <div class="col-md-2 text-end">
                    <a href="{{ action("Admin\AdminGroupController@create") }}" role="button" class="btn btn-secondary">
                        <span class="material-icons-round">
add
</span> {{ trans('messages.create_admin_group') }}
                    </a>
                </div>
            @endcan
        </div>

        <div class="pml-table-container"></div>
    </form>

@endsection
