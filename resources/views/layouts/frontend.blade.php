<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{ \Acelle\Model\Setting::get("site_description") }}">
	<meta name="keywords" content="{{ \Acelle\Model\Setting::get("site_keyword") }}" />
	<meta name="php-version" content="{{ phpversion() }}" />

	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

	@include('layouts._favicon')

	<!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">

	<!-- jQuery -->
	<script type="text/javascript" src="{{ URL::asset('core/js/jquery-3.6.0.min.js') }}"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/bootstrap/css/bootstrap.min.css') }}">
	<script type="text/javascript" src="{{ URL::asset('core/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

	<!-- Select2 -->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/select2/css/select2.min.css') }}">
	<script type="text/javascript" src="{{ URL::asset('core/select2/js/select2.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('core/js/validate.js') }}"></script>

	<!-- Google icon -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined"rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round"rel="stylesheet">

	<!-- Autofill -->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/autofill.css') }}">
	<script type="text/javascript" src="{{ URL::asset('core/js/autofill.js') }}"></script>

	<!-- AcelleMail -->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/app.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/mc.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/app.css') }}">

	<script type="text/javascript" src="{{ URL::asset('core/js/popup.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('core/js/list.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('core/js/anotify.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('core/js/dialog.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('core/js/iframe_modal.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('core/js/functions.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('core/js/app.js') }}"></script>

	<!-- JS vars -->
	@include('layouts._script_vars')
</head>
<body>
	<nav class="navbar navbar-expand-xl navbar-dark bg-dark fixed-top navbar-main py-0">
		<div class="container-fluid ms-0">
			<a class="navbar-brand d-flex align-items-center me-0" href="{{ action('HomeController@index') }}">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
                    <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
                @else
                    <img class="logo" src="{{ URL::asset('images/logo-' . (Auth::user()->customer->getColorScheme() == "white" ? "dark" : "light") . '.svg') }}" alt="">
                @endif
			</a>
			<button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			@php			
				$iconDir = "";
				if (Auth::user()->customer->getColorScheme() == 'white') {
					$iconDir = "dark/";
				}
			@endphp
	
			<div class="collapse navbar-collapse" id="navbarsExample04">
				<ul class="navbar-nav me-auto mb-md-0">
					<li class="nav-item">
						<a href="{{ action('HomeController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
							<i class="navbar-icon">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/home.svg') }}" />
							</i>
							<span>{{ trans('messages.dashboard') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ action('CampaignController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
							<i class="navbar-icon">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/campaign.svg') }}" />
							</i>
							<span>{{ trans('messages.campaigns') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ action('Automation2Controller@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
							<i class="navbar-icon">
								<img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/automation.svg') }}" />
							</i>
							<span>{{ trans('messages.automations') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ action('MailListController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
							<i class="navbar-icon">
								<img style="width: 21px" src="{{ url('images/icons/'.$iconDir.'SVG/list.svg') }}" />
							</i>
							<span>{{ trans('messages.lists') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1">
							<i class="navbar-icon">
								<img style="width: 18px" src="{{ url('images/icons/'.$iconDir.'SVG/template.svg') }}" />
							</i>
							<span>{{ trans('messages.templates') }}</span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="navbar-icon">
								<img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/content.svg') }}" />
							</i>
							<span>{{ trans('messages.content') }}</span>
						</a>
						<ul class="dropdown-menu shadow" aria-labelledby="content-menu">
							<li>
								<a class="dropdown-item d-flex align-items-center" href="{{ action('ProductController@index') }}">
									<i class="navbar-icon" style="">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/product-icon.svg') }}" />
									</i>
									<span>{{ trans('messages.products') }}</span>
								</a>
							</li>
							<li>
								<a class="dropdown-item d-flex align-items-center" href="{{ action('SourceController@index') }}">
									<i class="navbar-icon" style="">
										<img style="width: 18px" src="{{ url('images/icons/'.$iconDir.'SVG/store.svg') }}" />
									</i>
									<span>{{ trans('messages.stores_connections') }}</span>
								</a>
							</li>
						</ul>
					</li>
					@if (
						Auth::user()->customer->can("read", new Acelle\Model\SendingServer()) ||					
						Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()) ||
						Auth::user()->customer->can("read", new Acelle\Model\Blacklist()) ||
						true
					)
						<li class="nav-item dropdown"
							rel0="SendingServerController"
							rel1="SendingDomainController"
							rel2="SenderController"
							rel3="EmailVerificationServerController"
							rel4="BlacklistController"
							rel5="TrackingDomainController"
						>
							<a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="sending-menu" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="navbar-icon">
									<img style="width: 17px" src="{{ url('images/icons/'.$iconDir.'SVG/sending.svg') }}" />
								</i>
								<span>{{ trans('messages.sending') }}</span>
							</a>
							<ul class="dropdown-menu" aria-labelledby="sending-menu">
								@if (Auth::user()->customer->can("read", new Acelle\Model\SendingServer()))
									<li rel0="SendingServerController">
										<a href="{{ action('SendingServerController@index') }}" class="dropdown-item d-flex align-items-center">
											<i class="navbar-icon mr-3" style="width:19px">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
										</i> {{ trans('messages.sending_servers') }}
										</a>
									</li>
								@endif
								<li rel1="SendingDomainController">
									<a href="{{ action('SendingDomainController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3" style="width:20	px">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/domain.svg') }}" />
										</i> {{ trans('messages.sending_domains') }}
									</a>
								</li>
								<li rel0="SenderController">
									<a href="{{ action('SenderController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3" style="width:20	px">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/identify.svg') }}" />
										</i> {{ trans('messages.verified_senders') }}
									</a>
								</li>
								<li rel0="TrackingDomainController">
									<a href="{{ action('TrackingDomainController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3" style="width:20px">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/domain.svg') }}" />
										</i> {{ trans('messages.tracking_domains') }}
									</a>
								</li>
								@if (Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()))
									<li rel0="EmailVerificationServerController">
										<a href="{{ action('EmailVerificationServerController@index') }}" class="dropdown-item d-flex align-items-center">
										<i class="navbar-icon mr-3" style="width:20px">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
										</i> {{ trans('messages.email_verification_servers') }}
										</a>
									</li>
								@endif
								@if (Auth::user()->customer->can("read", new Acelle\Model\Blacklist()))
									<li rel0="BlacklistController">
										<a href="{{ action('BlacklistController@index') }}" class="dropdown-item d-flex align-items-center">
										<i class="navbar-icon mr-3" style="width:20px">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/blacklist.svg') }}" />
										</i> {{ trans('messages.blacklist') }}
										</a>
									</li>
								@endif
							</ul>
						</li>
					@endif
				</ul>
				<div class="navbar-right">
					<ul class="navbar-nav me-auto mb-md-0">
						@include('layouts._top_activity_log')
						<li class="nav-item dropdown">
							<a href="{{ action('TemplateController@index') }}" class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle" id="content-menu" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="navbar-icon">
									<img src="{{ Auth::user()->getProfileImageUrl() }}" style="border-radius:100%;transform:scale(1.5)" alt="">
								</i>
								<span>{{ Auth::user()->displayName() }}</span>
								@if (Auth::user()->customer->hasSubscriptionNotice())
									<i class="material-icons-round customer-warning-icon text-danger" style="right:2px;color:red!important;top:2px;position:absolute">info</i>
								@endif
							</a>
							<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="content-menu">
								@can("admin_access", Auth::user())
									<li><a href="{{ action("Admin\HomeController@index") }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/changeview.svg') }}" />
									</i>{{ trans('messages.admin_view') }}</a></li>
									<li class="divider"></li>
								@endif
								@if (request()->user()->customer->activeSubscription())
									<li class="dropdown">
										<a href="#" class="dropdown-item top-quota-button d-flex align-items-center" data-url="{{ action("AccountController@quotaLog") }}">
										<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/sreport.svg') }}" />
									</i>
											<span class="">{{ trans('messages.used_quota') }}</span>
										</a>
									</li>
								@endif
								<li rel0="AccountSubscriptionController\index">
									<a href="{{ action('AccountSubscriptionController@index') }}" class="dropdown-item position-relative d-flex align-items-center">
									<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/subscription.svg') }}" />
									</i>{{ trans('messages.subscriptions') }}
										@if (Auth::user()->customer->hasSubscriptionNotice())
											<i class="material-icons-outlined subscription-warning-icon text-danger" style="font-size: inherit;
												position: absolute;
												right: 0;
												top: 0;">info</i>
										@endif
									</a>
								</li>
								<li><a href="{{ action("AccountController@billing") }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/billing.svg') }}" />
									</i>{{ trans('messages.billing') }}
								
								</a></li>
								<li><a href="{{ action("AccountController@profile") }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
									</i>{{ trans('messages.account') }}
								
								</a></li>
								@if (Auth::user()->customer->canUseApi())
									<li rel0="AccountController/api">
										<a href="{{ action("AccountController@api") }}" class="dropdown-item d-flex align-items-center">
										<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/api.svg') }}" />
									</i>{{ trans('messages.campaign_api') }}
										</a>
									</li>
								@endif
								<li><a href="{{ url("/logout") }}" class="dropdown-item d-flex align-items-center">
									<i class="navbar-icon mr-3">
										<img src="{{ url('images/icons/'.$iconDir.'SVG/logout.svg') }}" />
									</i>{{ trans('messages.logout') }}
								</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<main class="container page-container px-3">
		@yield('page_header')

		<!-- display flash message -->
		@include('common.errors')

		<!-- main inner content -->
		@yield('content')

		<!-- Footer -->
		<div class="footer text-muted mt-5 py-3">
			{!! trans('messages.copy_right') !!}
		</div>
		<!-- /footer -->
	</main>

	<!-- Admin area -->
	@if (null !== Session::get('orig_customer_id') && Auth::user()->customer)
		<a href="{{ action('CustomerController@adminArea') }}" class="user-switch-area mc-modal-control">
			{{ trans('messages.customer.admin_area') }}
		</a>
	@endif

	{!! \Acelle\Model\Setting::get('custom_script') !!}

	<!-- Notification -->
	@include('layouts._notify')
	@include('layouts._notify_frontend')

	<!-- display flash message -->
	@include('common.flash')
</body>
</html>



{{-- <!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

	@include('layouts._favicon')

	@include('layouts._head')

	@include('layouts._css')

	@include('layouts._js')
	
	<!-- Custom langue -->
	<script>
		var LANG_CODE = 'en-US';
	</script>
	@if (Auth::user()->customer->getLanguageCodeFull())
		<script type="text/javascript" src="{{ URL::asset('assets/datepicker/i18n/datepicker.' . Auth::user()->customer->getLanguageCodeFull() . '.js') }}"></script>
		<script>
			LANG_CODE = '{{ Auth::user()->customer->getLanguageCodeFull() }}';
		</script>
	@endif

	<script>
		$.cookie('last_language_code', '{{ Auth::user()->customer->getLanguageCode() }}');
	</script>

</head>

<body class="navbar-top color-scheme-{{ Auth::user()->customer->getColorScheme() }}">

	<!-- Main navbar -->
	<div class="navbar navbar-{{ Auth::user()->customer->getColorScheme() == "white" ? "default" : "inverse" }} navbar-fixed-top">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ action('HomeController@index') }}">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
                    <img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
                @else
                    <img src="{{ URL::asset('images/logo-' . (Auth::user()->customer->getColorScheme() == "white" ? "dark" : "light") . '.svg') }}" alt="">
                @endif
			</a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a class="mobile-menu-button" data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-menu7"></i></a></li>
			</ul>
		</div>
		@php			
			$iconDir = "";
			if (Auth::user()->customer->getColorScheme() == 'white') {
				$iconDir = "dark/";
			}
		@endphp
		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li rel0="HomeController">
					<a href="{{ action('HomeController@index') }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/home.svg') }}" />
						</i>
						{{ trans('messages.dashboard') }}
					</a>
				</li>
				<li rel0="CampaignController">
					<a href="{{ action('CampaignController@index') }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3">
						<img src="{{ url('images/icons/'.$iconDir.'SVG/campaign.svg') }}" />
						</i>
						{{ trans('messages.campaigns') }}
					</a>
				</li>
				<li rel0="Automation2Controller">
					<a href="{{ action('Automation2Controller@index') }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3" style="width:18px;height:18px">
						<img src="{{ url('images/icons/'.$iconDir.'SVG/automation.svg') }}" />
						</i>
						{{ trans('messages.Automations') }}
					</a>
				</li>
				<li
					rel0="MailListController"
					rel1="FieldController"
					rel2="SubscriberController"
					rel3="SegmentController"
				>
					<a href="{{ action('MailListController@index') }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3" style="width:22px">
						<img src="{{ url('images/icons/'.$iconDir.'SVG/list.svg') }}" />
						</i>
						{{ trans('messages.lists') }}
					</a>
				</li>
                <li rel0="TemplateController">
					<a href="{{ action('TemplateController@index') }}" class="d-flex align-items-center">
						<i class="acelle-icon mr-3" style="width:19px">
						<img src="{{ url('images/icons/'.$iconDir.'SVG/template.svg') }}" />
						</i>
						{{ trans('messages.templates') }}
					</a>
				</li>

				@if (config('custom.woo'))
					<li class="dropdown language-switch"
						rel0="ProductController"
						rel1="SourceController"
					>
						<a data-toggle="dropdown" class="dropdown-toggle d-flex align-items-center">
							<i class="acelle-icon mr-3" style="width:19px">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/content.svg') }}" />
							</i> <span>{{ trans('messages.content') }}</span>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li rel0="ProductController">
								<a href="{{ action('ProductController@index') }}" class="d-flex align-items-center">
									<i class="acelle-icon mr-3" style="width: 24px;
									margin-left: -5px;
									margin-right: 8px!important;">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/product-icon.svg') }}" />
								</i> {{ trans('messages.products') }}
								</a>
							</li>
							<li rel0="SourceController">
								<a href="{{ action('SourceController@index') }}" class="d-flex align-items-center">
									<i class="acelle-icon mr-3" style="width:21px;margin-left: -4px;
									margin-right: 10px!important;">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/store.svg') }}" />
								</i> {{ trans('messages.stores_connections') }}
								</a>
							</li>
						</ul>
					</li>
				@endif
				
				@if (
					Auth::user()->customer->can("read", new Acelle\Model\SendingServer()) ||					
                    Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()) ||
					Auth::user()->customer->can("read", new Acelle\Model\Blacklist()) ||
					true
				)
					<li class="dropdown language-switch"
						rel0="SendingServerController"
						rel1="SendingDomainController"
						rel2="SenderController"
                        rel3="EmailVerificationServerController"
						rel4="BlacklistController"
						rel5="TrackingDomainController"
					>
						<a data-toggle="dropdown" class="dropdown-toggle d-flex align-items-center">
							<i class="acelle-icon mr-3" style="width:19px">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/sending.svg') }}" />
							</i> <span>{{ trans('messages.sending') }}</span>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->customer->can("read", new Acelle\Model\SendingServer()))
								<li rel0="SendingServerController">
									<a href="{{ action('SendingServerController@index') }}" class="d-flex align-items-center">
										<i class="acelle-icon mr-3" style="width:19px">
									<img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
									</i> {{ trans('messages.sending_servers') }}
									</a>
								</li>
							@endif
							<li rel1="SendingDomainController">
								<a href="{{ action('SendingDomainController@index') }}" class="d-flex align-items-center">
								<i class="acelle-icon mr-3" style="width:20	px">
									<img src="{{ url('images/icons/'.$iconDir.'SVG/domain.svg') }}" />
									</i> {{ trans('messages.sending_domains') }}
								</a>
							</li>
							<li rel0="SenderController">
								<a href="{{ action('SenderController@index') }}" class="d-flex align-items-center">
								<i class="acelle-icon mr-3" style="width:20	px">
									<img src="{{ url('images/icons/'.$iconDir.'SVG/identify.svg') }}" />
									</i> {{ trans('messages.verified_senders') }}
								</a>
							</li>
							<li rel0="TrackingDomainController">
								<a href="{{ action('TrackingDomainController@index') }}" class="d-flex align-items-center">
								<i class="acelle-icon mr-3" style="width:20px">
									<img src="{{ url('images/icons/'.$iconDir.'SVG/domain.svg') }}" />
									</i> {{ trans('messages.tracking_domains') }}
								</a>
							</li>
                            @if (Auth::user()->customer->can("read", new Acelle\Model\EmailVerificationServer()))
								<li rel0="EmailVerificationServerController">
									<a href="{{ action('EmailVerificationServerController@index') }}" class="d-flex align-items-center">
									<i class="acelle-icon mr-3" style="width:20px">
									<img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
									</i> {{ trans('messages.email_verification_servers') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->customer->can("read", new Acelle\Model\Blacklist()))
								<li rel0="BlacklistController">
									<a href="{{ action('BlacklistController@index') }}" class="d-flex align-items-center">
									<i class="acelle-icon mr-3" style="width:20px">
									<img src="{{ url('images/icons/'.$iconDir.'SVG/blacklist.svg') }}" />
									</i> {{ trans('messages.blacklist') }}
									</a>
								</li>
							@endif
						</ul>
					</li>
				@endif
			</ul>

			<ul class="nav navbar-nav navbar-right">

				@include('layouts._top_activity_log')

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ Auth::user()->getProfileImageUrl() }}" alt="">
						<span>{{ Auth::user()->displayName() }}</span>
						<i class="caret"></i>

						@if (Auth::user()->customer->hasSubscriptionNotice())
							<i class="material-icons customer-warning-icon text-danger">info</i>
						@endif
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						@can("admin_access", Auth::user())
							<li><a href="{{ action("Admin\HomeController@index") }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/changeview.svg') }}" />
							</i>{{ trans('messages.admin_view') }}</a></li>
							<li class="divider"></li>
						@endif
						@if (request()->user()->customer->activeSubscription())
							<li class="dropdown">
								<a href="#" class="top-quota-button d-flex align-items-center" data-url="{{ action("AccountController@quotaLog") }}">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/sreport.svg') }}" />
							</i>
									<span class="">{{ trans('messages.used_quota') }}</span>
								</a>
							</li>
						@endif
						<li rel0="AccountSubscriptionController\index">
							<a href="{{ action('AccountSubscriptionController@index') }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/subscription.svg') }}" />
							</i>{{ trans('messages.subscriptions') }}
								@if (Auth::user()->customer->hasSubscriptionNotice())
									<i class="material-icons-outlined subscription-warning-icon text-danger">info</i>
								@endif
							</a>
						</li>
						<li><a href="{{ action("AccountController@billing") }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/billing.svg') }}" />
							</i>{{ trans('messages.billing') }}
						
						</a></li>
						<li><a href="{{ action("AccountController@profile") }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
							</i>{{ trans('messages.account') }}
						
						</a></li>
						@if (Auth::user()->customer->canUseApi())
							<li rel0="AccountController/api">
								<a href="{{ action("AccountController@api") }}" class="level-1 d-flex align-items-center">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/api.svg') }}" />
							</i>{{ trans('messages.campaign_api') }}
								</a>
							</li>
						@endif
						<li><a href="{{ url("/logout") }}" class="d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/logout.svg') }}" />
							</i>{{ trans('messages.logout') }}
						</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->

	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content">

			@yield('page_header')

		</div>
	</div>
	<!-- /page header -->

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- display flash message -->
				@include('common.errors')

				<!-- main inner content -->
				@yield('content')

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->


		<!-- Footer -->
		<div class="footer text-muted">
			{!! trans('messages.copy_right') !!}
		</div>
		<!-- /footer -->

	</div>
	<!-- /page container -->

	@include("layouts._modals")

        {!! \Acelle\Model\Setting::get('custom_script') !!}

</body>
</html> --}}
