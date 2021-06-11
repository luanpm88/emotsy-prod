@extends('layouts.popup.large')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2>{{ trans('messages.campaign.choose_your_template_layout') }}</h2>
                
            <ul class="nav nav-tabs mc-nav campaign-template-tabs">
                <li class="active"><a href="{{ action('CampaignController@templateLayout', $campaign->uid) }}">Layouts</a></li>
                <li><a href="{{ action('CampaignController@templateTheme', $campaign->uid) }}">Themes</a></li>
                <li><a href="{{ action('CampaignController@templateUpload', $campaign->uid) }}">Upload</a></li>
            </ul>

            {{-- @if (count(Acelle\Model\Template::getTemplatesFromDisk(null, 'featured')))
                <div class="subsection">
                    <h2 class="font-weight-semibold mb-0">{{ trans('messages.template.featured') }}</h2>
                    <hr>

                    <div class="template-boxes" style="
                        margin-left: -20px;
                        margin-right: -20px;
                        clear: both;
                    ">
                        @foreach(Acelle\Model\Template::getTemplatesFromDisk(null, 'featured') as $layout)
                            <div class="col-xxs-12 col-xs-6 col-sm-3 col-md-2">
                                <a href="javascript:;" class="select-template-layout" data-layout="{{ $layout['name'] }}" data-type="featured">
                                    <div class="panel panel-flat panel-template-style">
                                        <div class="panel-body">
                                            <a 
                                                href="{{ action('CampaignController@templateLayout', [
                                                    'uid' => $campaign->uid,
                                                    'layout' => $layout['name'],
                                                    'type' => 'featured',
                                                ]) }}",
                                                class="choose-layout"
                                            >
                                                <img src="{{ $layout['thumb'] }}" />
                                                <label class="mb-20 text-center">{{ $layout['title'] }}</label>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <br style="clear:both" />
                </div>
            @endif --}}

			<div class="subsection">
				<h2 class="font-weight-semibold mb-0">{{ trans('messages.template.basic') }}</h2>
				<hr>

				<div class="template-boxes" style="
					margin-left: -20px;
					margin-right: -20px;
					clear: both;
				">
					@foreach(Acelle\Model\Template::getTemplatesFromDisk() as $layout)
						<div class="col-xxs-12 col-xs-6 col-sm-3 col-md-2">
							<a href="javascript:;" class="select-template-layout" data-layout="{{ $layout['name'] }}" data-type="basic">
								<div class="panel panel-flat panel-template-style">
									<div class="panel-body">
                                        <a 
                                            href="{{ action('CampaignController@templateLayout', [
                                                'uid' => $campaign->uid,
                                                'layout' => $layout['name'],
                                                'type' => 'basic',
                                            ]) }}",
                                            class="choose-layout"
                                        >
                                            <img src="{{ $layout['thumb'] }}" />
                                            <label class="mb-20 text-center">{{ $layout['title'] }}</label>
                                        </a>
									</div>
								</div>
							</a>
						</div>
					@endforeach
				</div>
				<br style="clear:both" />
			</div>
        </div>
    </div>
        
    <script>
        $('.campaign-template-tabs a').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
        
            templatePopup.load(url);
        });
        
        var builderSelectPopup = new Popup(null, undefined, {onclose: function() {
            window.location = '{{ action('CampaignController@template', $campaign->uid) }}';
        }});

        $('.choose-layout').click(function(e) {
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
                    notify('success', '{{ trans('messages.notify.success') }}', response.message);

                    builderSelectPopup.load(response.url);
                    templatePopup.hide();
                }
            });
        });
    </script>
@endsection