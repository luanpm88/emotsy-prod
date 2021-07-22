@extends('layouts.core.frontend')

@section('title', $list->name . " - " . number_with_delimiter($list->readCache('SubscriberCount')) . " " . trans('messages.subscribers'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h3 class="text-semibold text-primary">{{ trans('messages.list_performance') }}</h3>

    @include("lists._stat")

    <h3 class="text-semibold text-primary">{{ trans('messages.list_growth') }}</h3>

    @include("lists._growth_chart")
@endsection
