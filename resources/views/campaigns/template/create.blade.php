@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('head')      
    <script type="text/javascript" src="{{ AppUrl::asset('core/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/editor.js') }}"></script>
@endsection

@section('page_header')
	
    <div class="mt-3">
        <div class="border-bottom mb-4">
            <div class="" style="width: 100%">
                <div class="page-title pt-0">
                    <ul class="breadcrumb breadcrumb-caret position-right">
                        <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                    </ul>
                </div>
                <h2 class="mb-0 mb-3 d-flex align-items-center">
                    <span class="text-semibold">
                        <img width="25px" class="icon-img d-inline-block me-2" src="{{ url('images/icons/'.$campaign->type.'.svg') }}" />
                        <span>{{ $campaign->name }}</span>
                    </span>
                    <span class="text-center text-muted badge badge-info ms-2">{{ trans('messages.campaign.type.' . $campaign->type) }}</span>
                </h2>
                @include('campaigns._steps', ['current' => 3])
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            @if ($campaign->isABTest() && $campaign->ab_split_content)
                @include('campaigns.ab._template_variations')
            @endif

            <h3 class="mt-4">{{ trans('messages.campaign.email_content') }}</h3>                
            <p>{{ trans('messages.campaign.email_content.intro') }}</p>
                
            <ul class="hover-list">
                <li class="template-start" data-url="{{ action('CampaignController@templateLayout', [
                    'uid' => $campaign->uid,
                    'version_b' => request()->version_b,
                ]) }}">
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
                <li class="template-start" data-url="{{ action('CampaignController@templateUpload', [
                    'uid' => $campaign->uid,
                    'version_b' => request()->version_b,
                ]) }}">
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
