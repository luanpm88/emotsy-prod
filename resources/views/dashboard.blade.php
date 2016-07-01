@extends('layouts.frontend')

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
    <h1>{{ trans('messages.dashboard') }}</h1>
    
    <h2 class="text-teal-800">{{ trans('messages.recently_sent_campaigns') }}</h2>
    
    <div class="row">
        <div class="col-md-3">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'dashboard-campaign-select',
                'name' => 'campaign_id',
                'label' => '',
                'value' => '',
                'options' => Mailixa\Model\Campaign::getSelectOptions(Auth::user()),
            ])
        </div>
    </div>
    <div class="campaign-quickview-container" data-url="{{ action("CampaignController@quickView") }}"></div>
    
    <br />
    <br />
    
    <h2 class="text-teal-800">{{ trans('messages.list_growth') }}</h2>
    
    <div class="row">
        <div class="col-md-3">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'dashboard-list-select',
                'name' => 'list_id',
                'label' => '',
                'value' => '',
                'include_blank' => trans('messages.all'),
                'options' => Mailixa\Model\MailList::getSelectOptions(Auth::user()),
            ])
        </div>
    </div>
    <div class="list-quickview-container" data-url="{{ action("MailListController@quickView") }}"></div>
        
@endsection
