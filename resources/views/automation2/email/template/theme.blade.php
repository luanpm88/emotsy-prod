@extends('layouts.popup.medium')

@section('content')
	<p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
	
	@include('automation2.email._tabs', ['tab' => 'template'])
	
	<div class="row">
        <div class="col-md-12">
            <h4 class="mb-3 mt-2">{{ trans('messages.automation.choose_your_template_layout') }}</h4>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            @include('automation2.email.template._tabs', ['tab' => 'theme'])
			
			<div class="template-filter pt-4">
				<div class="d-flex align-items-center">
					<label class="font-weight-semibold mr-2">From</label>
					<select class="select" name="from">
						<option value="all">{{ trans('messages.all') }}</option>
						<option value="mine" selected='selected'>{{ trans('messages.my_templates') }}</option>
						<option value="gallery">{{ trans('messages.gallery') }}</option>
					</select>	
				</div>
			</div>
			<div class="template-list ajax-list">
				
			</div>
        </div>
    </div>
        
    <script>
		var listTheme = new List( $('.template-list'), {
			url: '{{ action('Automation2Controller@templateThemeList', [
					'uid' => $automation->uid,
					'email_uid' => $email->uid,
				]) }}',
			data: function() {
					return {
						from: $('[name=from]').val(),
					};
				},
		});
		
		listTheme.load();
		
		// filters
		$('[name=from]').change(function() {
			listTheme.load();
		});
		
    </script>
@endsection