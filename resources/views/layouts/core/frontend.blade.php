<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	@include('layouts.core._script_vars')

	@yield('head')
</head>
<body class="theme-{{ Auth::user()->customer->getColorScheme() }} {{ Auth::user()->customer->menu_layout }}bar
	{{ Auth::user()->customer->menu_layout == 'left' ? 'left' : 'top' }}bar-{{ request()->session()->get('customer-leftbar-state') }}
">
	@include('layouts.core._menu_frontend')

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
		<script>
			var AdminAreaPopup;

			$(function() {
				$('.user-switch-area').on('click', function(e) {
					e.preventDefault();
					AdminAreaPopup = new Popup({
						url: '{{ action('CustomerController@adminArea') }}',
					});

					AdminAreaPopup.load();
				});
			});
				
		</script>
	@endif

	<!-- Notification -->
	@include('layouts.core._notify')
	@include('layouts.core._notify_frontend')

	<!-- display flash message -->
	@include('layouts.core._flash')

	<script>
		// Customer color scheme | menu layout wizard
		@if (false)
			var wizardUserPopup;

			$(function() {
				wizardUserPopup = new Popup({
					url: '{{ action('AccountController@wizardColorScheme') }}',
				});
				wizardUserPopup.load();
			});
		@endif
	</script>

	<script>
		@if (null !== Session::get('orig_admin_id') && Auth::user()->admin)
			notify({
				type: 'warning',
				message: `{!! trans('messages.current_login_as', ["name" => Auth::user()->displayName()]) !!}<br>{!! trans('messages.click_to_return_to_origin_user', ["link" => action("Admin\AdminController@loginBack")]) !!}`,
				timeout: false,
			});
		@endif
	
		@if (null !== Session::get('orig_admin_id') && Auth::user()->admin)
			notify({
				type: 'warning',
				message: `{!! trans('messages.site_is_offline') !!}`,
				timeout: false,
			});
		@endif
	</script>

	{!! \Acelle\Model\Setting::get('custom_script') !!}
</body>
</html>