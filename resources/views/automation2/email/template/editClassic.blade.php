@extends('layouts.black')

@section('title', trans('messages.edit_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('content')
    <header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="height: 56px;">
			<a class="navbar-brand left-logo mr-0" href="#">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
					<img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
				@else
					<img height="22" src="{{ URL::asset('images/logo_light.png') }}" alt="">
				@endif
			</a>
			<div class="d-inline-block d-flex mr-auto align-items-center">
                <a style="" href="javascript;" class="close-button action black-back-button mr-3">
					<i class="material-icons-outlined">arrow_back</i>
				</a>
                <h1 class="">{{ $automation->name }}</h1>
				<i class="material-icons-outlined automation-head-icon ml-2">web</i>
			</div>
			<div class="automation-top-menu">
                <a class="action mr-4" href="{{ action('Automation2Controller@templateEditPlain', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}">
                    {{ trans('messages.email.plain_text_editor') }}
                </a>
				<button class="btn btn-primary mr-2" onclick="$('#classic-builder-form').submit()">{{ trans('messages.save') }}</button>
                
				<div class="account-info">
					<ul class="navbar-nav mr-auto navbar-dark bg-dark"">						
						<li class="nav-item dropdown">
							<a class="account-item nav-link dropdown-toggle px-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img class="avatar" src="{{ action('CustomerController@avatar', Auth::user()->customer->uid) }}" alt="">
								{{ Auth::user()->customer->displayName() }}
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
								@can("admin_access", Auth::user())
									<a class="dropdown-item d-flex align-items-center" href="{{ action("Admin\HomeController@index") }}">
										<i class="material-icons-outlined mr-2">double_arrow</i>
										{{ trans('messages.admin_view') }}
									</a>
									<div class="dropdown-divider"></div>
								@endif
								<a class="dropdown-item d-flex align-items-center quota-view" href="{{ action("AccountController@quotaLog2") }}">
									<i class="material-icons-outlined mr-2">multiline_chart</i>
									<span class="">{{ trans('messages.used_quota') }}</span>
								</a>
								<a class="dropdown-item d-flex align-items-center" href="{{ action('AccountSubscriptionController@index') }}">
									<i class="material-icons-outlined mr-2">redeem</i>
									<span>{{ trans('messages.subscriptions') }}</span>
								</a>
								<a class="dropdown-item d-flex align-items-center" href="{{ action("AccountController@profile") }}">
									<i class="material-icons-outlined mr-2">account_circle</i>
									<span>{{ trans('messages.account') }}</span>
								</a>
								@if (Auth::user()->customer->canUseApi())
									<a href="{{ action("AccountController@api") }}" class="dropdown-item d-flex align-items-center">
										<i class="material-icons-outlined mr-2">code</i>
										<span>{{ trans('messages.api') }}</span>
									</a>
								@endif
								<div class="dropdown-divider"></div>
								<a class="dropdown-item d-flex align-items-center" href="{{ url("/logout") }}">
									<i class="material-icons-outlined mr-2">power_settings_new</i>
									<span>{{ trans('messages.logout') }}</span>
								</a>
							</div>
						</li>
					</ul>
					
				</div>
			</div>
		</nav>
	</header>
    <form style="margin-top: 56px;" id="classic-builder-form" action="{{ action('Automation2Controller@templateEditClassic', [
        'uid' => $automation->uid,
        'email_uid' => $email->uid,
    ]) }}" method="POST" class="ajax_upload_form builder-classic-form form-validate-jquery">
        {{ csrf_field() }}

        <div class="row mr-0 ml-0">
            <div class="col-md-9 pl-0 pb-0 pr-0">
                @include('helpers.form_control', [
                    'class' => 'template-editor',
                    'required' => true,
                    'label' => '',
                    'type' => 'textarea',
                    'name' => 'content',
                    'value' => $email->render(),
                    'rules' => ['content' => 'required']
                ])           
            </div>
            <div class="col-md-3 pr-0 pb-0 sidebar pr-4 pt-4 pl-4" style="overflow:auto;background:#f5f5f5">
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags($automation->mailList)])
            </div>            
        </div>   
    <form>

    <script>
        $('.close-button').click(function() {
            parent.$('.full-iframe-popup').remove();
            popup.load();
        });

        $('.builder-classic-form').submit(function(e) {
            e.preventDefault();

            tinymce.triggerSave();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            if ($(this).valid()) {
                // open builder effects
                addMaskLoading("{{ trans('messages.automation.template.saving') }}", function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        statusCode: {
                            // validate error
                            400: function (res) {
                                console.log('Something went wrong!');
                            }
                        },
                        success: function (response) {
                            removeMaskLoading();

                            // notify
                            parent.notify('success', '{{ trans('messages.notify.success') }}', response.message);

                            parent.$('.full-iframe-popup').remove();
                            parent.popup.load();
                        }
                    });
                });         
            }     
        });

        $('.sidebar').css('height', parent.$('.full-iframe-popup').height()-56);

        var editor;
        $(document).ready(function() {
            editor = tinymce.init({
                selector: '.template-editor',
                height: parent.$('.full-iframe-popup').height()-56,
                convert_urls: false,
                remove_script_host: false,
                skin: "oxide-dark",
                forced_root_block: "",
                plugins: [
                'fullpage table advlist autolink lists link image charmap print preview anchor directionality',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media contextmenu paste code textcolor'
                ],
                toolbar: 'acelletags insertfile undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | ltr rtl',
                valid_elements : '*[*],meta[*]',
                extended_valid_elements : "meta[*]",
                valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
                content_css: [
                    APP_URL.replace('/index.php','')+'/tinymce/skins/lightgray/content.fixed.css',
                ],
                external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager2/",
                filemanager_title:"Responsive Filemanager" ,
                external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager2/plugin.min.js"},
                setup: function (editor) {
                    
                    /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                    /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                    editor.ui.registry.addMenuButton('acelletags', {
                        text: '{{ trans('messages.editor.insert_tag') }}',
                        fetch: function (callback) {
                        var items = [];

                        @foreach(Acelle\Model\Template::tags($automation->mailList) as $tag)
                            items.push({
                                type: 'menuitem',
                                text: '{{ "{".$tag["name"]."}" }}',
                                onAction: function (_) {
                                    editor.insertContent('{{ "{".$tag["name"]."}" }}');
                                }
                            });
                        @endforeach

                        callback(items);
                        }
                    });
                }
            });
        });
    </script>
@endsection

