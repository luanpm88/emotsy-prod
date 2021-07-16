<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')
</head>
<body>
	@include('layouts.core._menu_dark')

    @yield('page_header')

    <!-- display flash message -->
    @include('layouts.core._errors')

    <!-- main inner content -->
    @yield('content')

    <!-- Footer -->
    @include('layouts.core._footer')

	<!-- Notification -->
	@include('layouts.core._notify')
	@include('layouts.core._notify_frontend')

	<!-- display flash message -->
	@include('layouts.core._flash')

	{!! \Acelle\Model\Setting::get('custom_script') !!}
</body>
</html>