<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')

	<script>
		@if (Auth::user()->admin->theme_mode == 'auto')
			var ECHARTS_THEME = isDarkMode() ? 'dark' : null

			// auto detect dark-mode
			$(function() {
				autoDetechDarkMode('{{ action('Admin\AccountController@saveAutoThemeMode') }}');
			});
		@else
			var ECHARTS_THEME = '{{ Auth::user()->admin->theme_mode == 'dark' ? 'dark' : null }}';
		@endif
	</script>
</head>
<body class="theme-{{ Auth::user()->admin->getColorScheme() }} {{ Auth::user()->admin->getMenuLayout() }}bar
	{{ Auth::user()->admin->getMenuLayout() }}bar-{{ request()->session()->get('admin-leftbar-state') }}	
	
	@if (Auth::user()->admin->theme_mode == 'auto')
		@if (request()->session()->get('admin-auto-theme-mode'))
			mode-{{ request()->session()->get('admin-auto-theme-mode') }}
		@endif
	@else
		mode-{{ Auth::user()->admin->theme_mode }}
	@endif
">
	@include('layouts.core._menu_backend')

	@include('layouts.core._middle_bar')

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