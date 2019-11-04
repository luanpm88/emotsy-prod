@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h2>{{ trans('messages.campaign.choose_your_template_layout') }}</h2>
                
            <ul class="nav nav-tabs campaign-template-tabs">
                <li><a href="{{ action('CampaignController@templateLayout', $campaign->uid) }}">Layouts</a></li>
                <li><a href="{{ action('CampaignController@templateTheme', $campaign->uid) }}">Themes</a></li>
                <li class="active"><a href="{{ action('CampaignController@templateUpload', $campaign->uid) }}">Upload</a></li>
            </ul>
            
            <div class="alert alert-info">
                {!! trans('messages.template_upload_guide', ["link" => 'https://s3.amazonaws.com/acellemail/newsletter-template-green.zip']) !!}
            </div>
            
            <form enctype="multipart/form-data" action="{{ action('TemplateController@upload') }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}

                @include('helpers.form_control', ['required' => true, 'type' => 'text', 'label' => trans('messages.template_name'), 'name' => 'name', 'value' => old('name'), 'rules' => ['name' => 'required']])

                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file'])

                <div class="text-right">
                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.upload') }}</button>
                    <a href="{{ action('TemplateController@index') }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                </div>

            </form>
        </div>
    </div>
        
    <script>
        $('.campaign-template-tabs a').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
        
            templatePopup.load(url);
        });
    </script>
@endsection