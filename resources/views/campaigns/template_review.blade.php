@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', $campaign->name)

@section('page_header')

    @include("campaigns._header", [
        'menu' => 'template',
    ])

@endsection

@section('content')

    @if (!$campaign->template_id)
        <div class="row">
            <div class="col-md-6">
                @include('elements._notification', [
                    'level' => 'warning',
                    'message' => trans('messages.campaign.template.old_ver_error')
                ])
            </div>
        </div>
            
    @else
        <iframe class="preview_page_frame" src="{{ action('CampaignController@templateReviewIframe', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" />
    @endif

@endsection
