<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>
	
	@include('layouts._favicon')
	
	@include('layouts._head')

	@include('layouts._css')
	
	@include('layouts._js')
	
</head>

<body class="navbar-top  color-scheme-{{ Auth::user()->reseller->getColorScheme() }}">

	<!-- Main navbar -->
	<div class="navbar navbar-{{ Auth::user()->reseller->getColorScheme() == "white" ? "default" : "inverse" }} navbar-fixed-top">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ action('HomeController@index') }}">
				<img src="{{ URL::asset('assets/images/logo_' . (Auth::user()->reseller->getColorScheme() == "white" ? "dark" : "light") . '.png') }}" alt="">
			</a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a class="mobile-menu-button" data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-menu7"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li rel0="Reseller\HomeController">
					<a href="{{ action('Reseller\HomeController@index') }}">
						<i class="icon-home"></i> {{ trans('messages.dashboard') }}
					</a>
				</li>
				<li rel1="CustomerController">
					<a href="{{ action('Reseller\CustomerController@index') }}">
						<i class="icon-users"></i> {{ trans('messages.customers') }}
					</a>
				</li>
					
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ action('ResellerController@avatar', Auth::user()->reseller->uid) }}" alt="">
						<span>{{ Auth::user()->reseller->displayName() }}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						@can("customer_access", Auth::user())
							<li><a href="{{ action("HomeController@index") }}"><i class="icon-exit2"></i> {{ trans('messages.customer_view') }}</a></li>
							<li class="divider"></li>
						@endif
						@can("admin_access", Auth::user())
							<li><a href="{{ action("Admin\HomeController@index") }}"><i class="icon-enter2"></i> {{ trans('messages.admin_view') }}</a></li>
							<li class="divider"></li>
						@endif
						<li><a href="{{ action("Reseller\AccountController@profile") }}"><i class="icon-profile"></i> {{ trans('messages.account') }}</a></li>
						<li><a href="{{ url("/logout") }}"><i class="icon-switch2"></i> {{ trans('messages.logout') }}</a></li>
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
</body>
</html>
