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
        <div class="col-md-12">
            <h4 class="mb-3 mt-2">{{ trans('messages.automation.choose_your_template_layout') }}</h4>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            <ul class="nav nav-tabs campaign-template-tabs">
                <li class="nav-item">
					<a class="nav-link active" onclick="popup.load('{{ action('Automation2Controller@templateLayout', [
							'uid' => $automation->uid,
							'email_uid' => $email->uid,
						]) }}')"
					>
						Layouts
					</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" onclick="popup.load('{{ action('Automation2Controller@templateTheme', [
							'uid' => $automation->uid,
							'email_uid' => $email->uid,
						]) }}')"
					>
						Themes
					</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" onclick="popup.load('{{ action('Automation2Controller@templateUpload', [
							'uid' => $automation->uid,
							'email_uid' => $email->uid,
						]) }}')"
					>
						Upload
					</a>
				</li>
            </ul>
                
            <div id="layout" class="tab-pane row template-boxes layout mt-4" style="
                margin-left: -15px;
                margin-right: -15px;
            ">
                @foreach(Acelle\Model\Template::templateStyles() as $name => $style)
                    <div class="col-xxs-6 col-xs-4 col-sm-2 col-md-2">
                        <a data-method="POST"
							href="{{ action('Automation2Controller@templateLayout', [
								'uid' => $automation->uid,
								'email_uid' => $email->uid,
								'layout' => $name,
							]) }}"
							class="select-layout"
						>
                            <div class="panel panel-flat panel-template-style mb-4">
                                <div class="panel-body">									
									<img src="{{ url('images/template_styles/'.$name.'.png') }}" />
									<label class="mb-20 text-center">{{ trans('messages.'.$name) }}</label>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
        
    <script>
        $('a.select-layout').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
			
			popup.loading();
        
            $.ajax({
				url: url,
				type: 'POST',
				data: {
					_token: CSRF_TOKEN
				}
			}).always(function(response) {
				//// set node title
				//tree.getSelected().setTitle(response.title);
				//// merge options with reponse options
				//tree.getSelected().setOptions($.extend(tree.getOptions(), response.options));
				//tree.getSelected().setOptions($.extend(tree.getOptions(), {init: true}));
				//
				//popup.hide();
				//
				//notify('success', '{{ trans('messages.notify.success') }}', response.message);
				
				popup.load('{{ action('Automation2Controller@emailTemplate', [
					'uid' => $automation->uid,
					'email_uid' => $email->uid,
				]) }}');
				
				// open builder in iframe
				var url = '{{ action('Automation2Controller@templateEdit', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}';				
				var div = $('<div class="full-iframe-popup">').html('<iframe scrolling="no" class="builder" src="'+url+'"></iframe>');
				$('body').append(div);
			});
        });
    </script>
@endsection