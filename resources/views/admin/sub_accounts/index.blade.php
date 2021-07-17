@extends('layouts.core.backend')

@section('title', trans('messages.sub_accounts'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.sub_accounts') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <p>{{ trans('messages.sub_accounts.wording') }}</p>


    <form class="listing-form"
        data-url="{{ action('Admin\SubAccountController@listing') }}"
        per-page="{{ Acelle\Model\SubAccount::$itemsPerPage }}"
    >
        <div class="row top-list-controls py-2">
            <div class="col-md-10">
                @if ($accounts->count() >= 0)
                    <div class="filter-box">
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                            <select class="select" name="sort_order">
                                <option value="sending_servers.created_at">{{ trans('messages.created_at') }}</option>
                                <option value="sending_servers.name">{{ trans('messages.name') }}</option>
                                <option value="sending_servers.updated_at">{{ trans('messages.updated_at') }}</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="desc" />
<button class="btn btn-light sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                                <span class="material-icons-outlined desc">
sort
</span>
                            </button>
                        </span>
                        <span class="filter-group">
                            <span class="title text-semibold text-muted">{{ trans('messages.type') }}</span>
                            <select class="select" name="type">
                                <option value="">{{ trans('messages.all') }}</option>
                                @foreach (Acelle\Model\SendingServer::getSubAccountTypes() as $key => $type)
                                    <option value="{{ $type }}">{{ trans('messages.' . $type) }}</option>
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
                @endif
            </div>
        </div>

        <div class="pml-table-container">
        </div>
    </form>
@endsection
