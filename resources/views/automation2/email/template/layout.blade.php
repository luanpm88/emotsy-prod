@extends('layouts.popup.medium')

@section('content')
	
	@include('automation2.email._tabs', ['tab' => 'template'])
		
	<div class="row">
        <div class="col-md-12">
            <h5 class="mb-3 mt-2">{{ trans('messages.automation.choose_your_template_layout') }}</h5>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            @include('automation2.email.template._tabs', ['tab' => 'layout'])

			<div class="subsection pt-4">
				<h4 class="font-weight-semibold mb-0">{{ trans('messages.template.featured') }}</h4>
				<hr>

				<div id="layout" class="tab-pane row template-boxes layout mt-4" style="
					margin-left: -15px;
					margin-right: -15px;
				">
					@foreach(Acelle\Model\Template::woo()->get() as $layout)
						<div class="col-xxs-6 col-xs-4 col-sm-2 col-md-2">
							<a data-method="POST"
								href="{{ action('Automation2Controller@templateTheme', [
									'uid' => $automation->uid,
									'email_uid' => $email->uid,
									'template_uid' => $layout->uid,
								]) }}"
								class="select-layout"
							>
								<div class="panel panel-flat panel-template-style mb-4">
									<div class="panel-body">									
										<img src="{{ $layout->getThumbUrl() }}?v={{ rand(0,10) }}" />
										<label class="mb-20 text-center">{{ $layout->name }}</label>
										<span class="template_recommended">Recommended</span>
									</div>
								</div>
							</a>
						</div>
					@endforeach
				</div>
				<br style="clear:both" />
			</div>

			<div class="subsection">
				<h4 class="font-weight-semibold mb-0">{{ trans('messages.template.basic') }}</h2>
				<hr>

				<div id="layout" class="tab-pane row template-boxes layout mt-4" style="
					margin-left: -15px;
					margin-right: -15px;
				">
					@foreach(Acelle\Model\Template::basic()->get() as $layout)
						<div class="col-xxs-6 col-xs-4 col-sm-2 col-md-2">
							<a data-method="POST"
								href="{{ action('Automation2Controller@templateTheme', [
									'uid' => $automation->uid,
									'email_uid' => $email->uid,
									'template_uid' => $layout->uid,
								]) }}"
								class="select-layout"
							>
								<div class="panel panel-flat panel-template-style mb-4">
									<div class="panel-body">									
										<img src="{{ $layout->getThumbUrl() }}?v={{ rand(0,10) }}" />
										<label class="mb-20 text-center">{{ $layout->name }}</label>
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
		var builderSelectPopup = new Popup(null, undefined, {onclose: function() {
            
        }});

        $('a.select-layout').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
			
			popup.loading();
			
			// loading popup
			popup.loading();
			
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					_token: CSRF_TOKEN,
				}
			}).always(function(response) {
				popup.load('{{ action('Automation2Controller@emailTemplate', [
					'uid' => $automation->uid,
					'email_uid' => $email->uid,
				]) }}');

				builderSelectPopup.load('{{ action('Automation2Controller@templateBuilderSelect', [
					'uid' => $automation->uid,
					'email_uid' => $email->uid,
				]) }}');

				// notify
				notify('success', '{{ trans('messages.notify.success') }}', response.message);
			});
        });
    </script>
@endsection