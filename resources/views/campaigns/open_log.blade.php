@extends('layouts.core.frontend')

@section('title', $campaign->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    @include("campaigns._header")

@endsection

@section('content')

@include("campaigns._menu")

<h2 class="text-semibold text-teal-800">{{ trans('messages.open_log') }}</h2>

<form class="listing-form"
        data-url="{{ action('CampaignController@openLogListing', $campaign->uid) }}"
        per-page="{{ Acelle\Model\OpenLog::$itemsPerPage }}"
    >
        <div class="row top-list-controls py-2">
            <div class="col-md-10">
                @if ($items->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="created_at">{{ trans('messages.created_at') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="desc" />
<button class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-icons-outlined desc">
sort
</span>
                            </button>
                        </span>
                        <span class="text-nowrap">
                            <input name="search_keyword" value="{{ request()->search_keyword }}" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
                            <span class="material-icons-round">
search
</span>
                        </span>
                    </div>
                @endif
            </div>
            <div class="col-md-2 text-end">
                <a href="javascript:goToDownLoad('open_logs')" role="button" class="btn btn-secondary">
                    <i class="icon icon-download mr-5"></i> {{ trans('messages.open_log.download') }}
                </a>
            </div>
        </div>

        <div class="pml-table-container">
        </div>
    </form>
@endsection
