@extends('layouts.popup.medium')

@section('content')	
	<p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
		
	<ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')">
                <i class="lnr lnr-cog mr-2"></i>
                {{ trans('messages.automation.email.setup') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
                @if (isset($email->id))                    
                    onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}')"
                @endif
            >
                <i class="lnr lnr-layers mr-2"></i>
                {{ trans('messages.automation.email.content') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
                @if (isset($email->id))                    
                    onclick="popup.load('{{ action('Automation2Controller@emailConfirm', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}')"
                @endif
            >
                <i class="lnr lnr-cog mr-2"></i>
                {{ trans('messages.automation.email.confirm') }}
            </a>
        </li>
    </ul>
	
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
