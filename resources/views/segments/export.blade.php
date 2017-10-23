@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.export'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h2 class="text-bold text-teal-800"><i class="icon-users4"></i> {{ trans('messages.export_segments') }}</h2>

    @include("helpers._export",['url' => action('SegmentController@exportList', ["list_uid" => $list->uid]) ])

@endsection
