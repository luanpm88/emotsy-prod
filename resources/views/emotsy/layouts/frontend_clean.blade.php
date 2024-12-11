<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')

	<!-- Theme -->
    <link rel="stylesheet" type="text/css" href="{{ AppUrl::asset('core/css/theme/'.Auth::user()->customer->getColorScheme().'.css') }}?v=6">
</head>
<body class="topbar emotsy-clean">
	<div class="container-fluid ms-0 mb-5">
		<div class="d-flex align-items-center mx-3 my-2">
			<div>
				<a style="width:150px;" class="navbar-brand d-flex align-items-center me-2" href="{{ action('HomeController@index') }}">
					<img style="width:120px;" class="logo" src="{{ getSiteLogoUrl('dark') }}" alt="">
				</a>
			</div>
			<div class="d-flex mr-auto align-items-center ml-1">
				<i class="material-symbols-rounded me-2 fs-5 text-warning">verified_user</i>
				<span class="my-0 me-2 pl-1 mt-1">{{ trans('messages.subscription.secure_checkout') }}</span>
			</div>
		</div>
			
    </div>

	@include('layouts.core._middle_bar')

    @yield('page_header')

    <!-- main inner content -->
    @yield('content')

	<!-- Notification -->
	@include('layouts.core._notify')
	@include('layouts.core._notify_frontend')

	<!-- display flash message -->
	@include('layouts.core._flash')

	<!-- Admin area -->
	@include('layouts.core._admin_area')

	{!! \Acelle\Model\Setting::get('custom_script') !!}
</body>
</html>