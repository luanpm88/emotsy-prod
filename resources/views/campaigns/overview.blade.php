@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', $campaign->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/echarts/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/echarts/dark.js') }}"></script> 
@endsection

@section('page_header')

    @include("campaigns._header", [
        'menu' => 'overview',
    ])

@endsection

@section('content')

    @include("campaigns._info")

    <br>

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

    <br />

    @include("campaigns._chart")

    @include("campaigns._open_click_rate")

    @include("campaigns._count_boxes")

    <br />

    @include("campaigns._24h_chart")


    <br />

    @include("campaigns._top_link")

    <br />

    @include("campaigns._most_click_country")

    <br />

    @include("campaigns._most_open_country")

    <br />

    @include("campaigns._most_open_location")

    <br />
@endsection
