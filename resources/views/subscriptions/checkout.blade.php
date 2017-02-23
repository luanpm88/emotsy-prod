@extends('layouts.subscription')

@section('title', trans('messages.checkout'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
    @include('subscriptions._steps')
    
    
    
@endsection
