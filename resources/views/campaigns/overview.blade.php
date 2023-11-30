@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', $campaign->name)

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('page_header')

    @include("campaigns._header", [
        'menu' => 'overview',
    ])

    @if ($campaign->isABTest())
        @include('campaigns.ab._insights_version_tabs', [
            'link_a' => action('CampaignController@overview', [
                'uid' => $campaign->uid,
            ]),
            'link_b' => action('CampaignController@overview', [
                'uid' => $campaign->uid,
                'version_b' => true,
            ]),
        ])
    @endif

@endsection

@section('content')

    @include("campaigns._info")

    <br />

    @include("campaigns._chart")

    @include("campaigns._open_click_rate", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])

    @include("campaigns._count_boxes", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])

    <br />

    @include("campaigns._24h_chart", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])


    <br />

    @include("campaigns._top_link", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])

    <br />

    @include("campaigns._most_click_country", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])

    <br />

    @include("campaigns._most_open_country", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])

    <br />

    @include("campaigns._most_open_location", [
        'campaign' => ($campaign->isABTest() && request()->version_b) ? $campaign->campaignB : $campaign,
    ])

    <br />
@endsection
