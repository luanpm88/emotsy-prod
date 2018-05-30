@extends('layouts.backend')

@section('title', trans('messages.sending_domains'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.sending_domains') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-10 col-lg-10">
            <p>{!! trans('messages.sending_domain.wording') !!}</p>
        </div>
    </div>

    <form class="listing-form"
        sort-url="{{ action('Admin\SendingDomainController@sort') }}"
        data-url="{{ action('Admin\SendingDomainController@listing') }}"
        per-page="{{ Acelle\Model\SendingDomain::$itemsPerPage }}"
    >
        <div class="row top-list-controls">
            <div class="col-md-9">
                
            </div>
            @if (Auth::user()->admin->can('create', new Acelle\Model\SendingDomain()))
                <div class="col-md-3 text-right">
                    <a href="{{ action('Admin\SendingDomainController@create') }}" type="button" class="btn bg-info-800">
                        <i class="icon icon-plus2"></i> {{ trans('messages.create_sending_domain') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="pml-table-container">



        </div>
    </form>
@endsection
