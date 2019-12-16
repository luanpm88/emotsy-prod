@extends('layouts.popup.medium')

@section('content')	
	<p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
		
	@include('automation2.email._tabs', ['tab' => 'template'])
	
    <div class="row">
        <div class="col-md-10">
            <h4 class="mb-3">{{ trans('messages.automation.template.email_content') }}</h4>                
            <p>{{ trans('messages.automation.template.email_content.intro') }}</p>
            
			<div class="row">
				<div class="col-md-10">
					<ul class="hover-list">
						<li class="template-start" data-url="{{ action('Automation2Controller@templateLayout', [
								'uid' => $automation->uid,
								'email_uid' => $email->uid,
							]) }}"
						>
							<i class="lnr lnr-file-add"></i>
							<div class="list-body">
								<label>{{ trans('messages.automation.template.from_layout') }}</label>
								<p>{{ trans('messages.automation.template.from_layout.intro') }}</p>
							</div>
							<div class="list-action">
								<button
									class="btn btn-secondary"
								>
									{{ trans('messages.automation.template.start') }}
								</button>
							</div>
						</li>
						<li class="template-start" data-url="{{ action('Automation2Controller@templateTheme', [
								'uid' => $automation->uid,
								'email_uid' => $email->uid,
							]) }}"
						>
							<i class="lnr lnr-license"></i>
							<div class="list-body">
								<label>{{ trans('messages.automation.template.from_theme') }}</label>
								<p>{{ trans('messages.automation.template.from_theme.intro') }}</p>
							</div>
							<div class="list-action">
								<button
									class="btn btn-secondary"
								>
									{{ trans('messages.automation.template.start') }}
								</button>
							</div>
						</li>
						<li class="template-start" data-url="{{ action('Automation2Controller@templateUpload', [
								'uid' => $automation->uid,
								'email_uid' => $email->uid,
							]) }}"
						>
							<i class="lnr lnr-user"></i>
							<div class="list-body">
								<label>{{ trans('messages.automation.template.upload') }}</label>
								<p>{{ trans('messages.automation.template.upload.intro') }}</p>
							</div>
							<div class="list-action">
								<button
									class="btn btn-secondary template-start"
								>
									{{ trans('messages.automation.template.start') }}
								</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
        </div>
    </div>
        
    <script>
        $(document).ready(function() {
        
            $('.template-start').click(function() {
				var url = $(this).attr('data-url');
				
                popup.load(url);
            });
        
        });
    </script>
@endsection
