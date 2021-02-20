@extends('layouts.popup.small')

@section('content')     
    @include('automation2._tabs_timeline', ['tab' => 'statistics', 'sub' => trans('messages.automation.timeline')])
        
    <div class="timlines_list ajax-list"></div>
        
    <script>
        var listTimeline = new List( $('.timlines_list'), {
            url: '{{ action('Automation2Controller@timelineList', [
                    'uid' => $automation->uid,
                ]) }}',
            per_page: 5,
        });		
        listTimeline.load();
    </script>
@endsection