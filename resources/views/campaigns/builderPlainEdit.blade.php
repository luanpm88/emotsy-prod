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
                <a style="" href="{{ action('CampaignController@template', $campaign->uid) }}" class="action black-back-button mr-3">
					<i class="material-icons-outlined">arrow_back</i>
				</a>
                <h1 class="">{{ $campaign->name }}</h1>
				<i class="material-icons-outlined automation-head-icon ml-2">web</i>
			</div>
			<div class="automation-top-menu">
                <a class="action mr-4" href="{{ action('CampaignController@builderClassic', [
                    'uid' => $campaign->uid
                ]) }}">
                    {{ trans('messages.campaign.html_editor') }}
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
    <form style="margin-top: 56px !important" id="classic-builder-form" action="{{ action('CampaignController@builderPlainEdit', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="row mr-0 ml-0">
            <div class="col-md-9 pl-0 pb-0 pr-0">
                @include('helpers.form_control', ['type' => 'textarea',
                    'class' => 'campaign-plain-text',
                    'name' => 'plain',
                    'label' => '',
                    'value' => $campaign->plain,
                    'rules' => [],
                    'help_class' => 'campaign'
                ])          
            </div>
            <div class="col-md-3 pr-0 pb-0 sidebar pr-4 pt-4 pl-4" style="overflow:auto;background:#f5f5f5">
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags($campaign->defaultMailList)])
            </div>            
        </div>   
    <form>

    <script>
        $('.sidebar').css('height', $(window).height()-56);
        $('[name=plain]').css('height', $(window).height()-56);
    </script>
@endsection
