@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.schedule'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/datetime/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/datetime/pickadate/picker.date.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-icons-outlined me-2">
forward_to_inbox
</span> {{ $campaign->name }}</span>
        </h1>

        @include('campaigns._steps', ['current' => 4])
    </div>

@endsection

@section('content')
    <form action="{{ action('CampaignController@schedule', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-3 list_select_box" target-box="segments-select-box" segments-url="{{ action('SegmentController@selectBox') }}">
                @include('helpers.form_control', ['type' => 'date',
                    'class' => '_from_now',
                    'name' => 'delivery_date',
                    'label' => trans('messages.delivery_date'),
                    'value' => $delivery_date,
                    'rules' => $rules,
                    'help_class' => 'campaign'
                ])
            </div>
            <div class="col-md-3 segments-select-box">
                @include('helpers.form_control', ['type' => 'time',
                    'name' => 'delivery_time',
                    'label' => trans('messages.delivery_time'),
                    'value' => $delivery_time,
                    'rules' => $rules,
                    'help_class' => 'campaign'
                ])
            </div>
        </div>
        
        <hr>
        <div class="text-end">
            <button class="btn btn-secondary">{{ trans('messages.save_and_next') }} <span class="material-icons-outlined">
arrow_forward
</span> </button>
        </div>
        
    <form>
        
    <script>
        $(document).ready(function() {
            // Pick a date
            $('.pickadate_from_now').pickadate({
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
