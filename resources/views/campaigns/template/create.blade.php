@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('head')      
    <script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')
	
    <div class="topfix-header bg-light">
        <div class="topfix-container border-bottom d-flex align-items-center shadow-sm py-3 mb-4">
            <div class="topfix-search text-center" style="width: 100%">
                <div class="page-title">
                    <ul class="breadcrumb breadcrumb-caret position-right">
                        <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                    </ul>
                </div>
                <h3 class="mb-0 mb-3">
                    <span class="text-semibold">
                        <img width="25px" class="icon-img d-inline-block me-2" src="{{ url('images/icons/'.$campaign->type.'.svg') }}" />
                        <span>{{ $campaign->name }}</span>
                    </span>
                </h3>
                @include('campaigns._steps', ['current' => 3])
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-0">{{ trans('messages.campaign.content_management') }}</h1>
            <h3 class="mt-4">{{ trans('messages.campaign.email_content') }}</h3>                
            <p>{{ trans('messages.campaign.email_content.intro') }}</p>
                
            <ul class="hover-list">
                <li class="template-start" data-url="{{ action('CampaignController@templateLayout', $campaign->uid) }}">
                    <img width="35px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/plus.svg') }}" />
                    <div class="list-body">
                        <h4>{{ trans('messages.campaign.template.from_layout') }}</h4>
                        <p class="text-muted">{{ trans('messages.campaign.template.from_layout.intro') }}</p>
                    </div>
                    <div class="list-action">
                        <button
							class="btn btn-primary bg-grey-800"
						>
                            {{ trans('messages.campaign.template.start') }}
                        </button>
                    </div>
                </li>
                <li class="template-start" data-url="{{ action('CampaignController@templateUpload', $campaign->uid) }}">
                    <img width="35px" class="icon-img d-inline-block me-4" src="{{ url('images/icons/upload.svg') }}" />
                    <div class="list-body">
                        <h4>{{ trans('messages.campaign.template.upload') }}</h4>
                        <p class="text-muted">{{ trans('messages.campaign.template.upload.intro') }}</p>
                    </div>
                    <div class="list-action">
                        <button
							class="btn btn-primary bg-grey-800"
						>
                            {{ trans('messages.campaign.template.start') }}
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
        
    <script>
		var templatePopup = new Popup();
    
        $(document).ready(function() {
        
            $('.template-start').click(function() {
				var url = $(this).attr('data-url');
				
                templatePopup.load(url);
            });
        
        });

        $(document).on('click', '.choose-theme', function(e) {
            e.preventDefault();        
            var url = $(this).attr('href');

            addMaskLoading();

            // 
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                },
                statusCode: {
                    // validate error
                    400: function (res) {
                        alert('Something went wrong!');
                    }
                },
                success: function (response) {
                    removeMaskLoading();

                    // notify
                    notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});

                    builderSelectPopup.load(response.url);
                    templatePopup.hide();
                }
            });
        });
    </script>

@endsection
