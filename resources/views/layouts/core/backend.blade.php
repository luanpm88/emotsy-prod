<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')
</head>
<body class="theme-{{ Auth::user()->admin->getColorScheme() }} {{ Auth::user()->admin->menu_layout }}bar
	{{ Auth::user()->admin->menu_layout }}bar-{{ request()->session()->get('admin-leftbar-state') }}	
">
	@include('layouts.core._menu_backend')

	<main class="container page-container px-3">
		@yield('page_header')

		<!-- display flash message -->
		@include('layouts.core._errors')

		<!-- main inner content -->
		@yield('content')

		<!-- Footer -->
		@include('layouts.core._footer')
	</main>

	<!-- Admin area -->
	@if (null !== Session::get('orig_customer_id') && Auth::user()->customer)
		<a href="{{ action('CustomerController@adminArea') }}" class="user-switch-area mc-modal-control">
			{{ trans('messages.customer.admin_area') }}
		</a>
	@endif

	<!-- Notification -->
	@include('layouts.core._notify')

	<!-- display flash message -->
	@include('layouts.core._flash')

	{!! \Acelle\Model\Setting::get('custom_script') !!}
</body>
</html>