@extends('layouts.frontend')

@section('title', trans('messages.blacklist'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.blacklist') }}</span>
        </h1>
    </div>
@endsection

@section('content')

    <form class="listing-form"
        data-url="{{ action('BlacklistController@listing') }}"
        per-page="{{ Acelle\Model\Blacklist::$itemsPerPage }}"
    >

        <div class="row top-list-controls py-2">
            <div class="col-md-9">
                @if ($blacklists->count() >= 0)
                    <div class="filter-box">
                        @include('helpers.select_tool')
                        <div class="btn-group list_actions hide">
                            <button role="button" class="btn btn-xs btn-grey-600 dropdown-toggle" data-toggle="dropdown">
                                {{ trans('messages.actions') }} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a delete-confirm="{{ trans('messages.remove_blacklist_confirm') }}" href="{{ action('BlacklistController@delete') }}"><span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
                            </ul>
                        </div>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="blacklists.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="blacklists.email">{{ trans('messages.email') }}</option>
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
            <div class="col-md-3">
                @if (Auth::user()->customer->can('import', new Acelle\Model\Blacklist()))
                    <div class="text-end">
                        <a href="{{ action('BlacklistController@import') }}" role="button" class="btn btn-secondary">
                            <i class="icon-download4"></i> {{ trans('messages.blacklist.import') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="pml-table-container">
        </div>
    </form>
@endsection
