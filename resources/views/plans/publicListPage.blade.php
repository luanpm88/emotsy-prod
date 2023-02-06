<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

	@include('layouts.core._head')
</head>

<body class="" style="background-color:#eee">
    

	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content">

			@yield('page_header')

		</div>
	</div>
	<!-- /page header -->

	<!-- Page container -->
	<div class="page-container" style="min-height: 100vh">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

                @if (Auth::check())
                    <div class="alert alert-warning">
                        <span>
                            {{ trans('messages.select_plan.logged_in.logout_current_account', [
                                'username' => Auth::user()->email,
                            ]) }}
                        </span>
                        <div class="mt-2">
                            <button data-action="logout"  class="btn btn-secondary">
                                <i class="icon-switch2"></i> {{ trans('messages.logout') }}
                            </button>
                        </div>
                    </div>

                    <script>
                        $(function() {
                            $('[data-action="logout"]').on('click', function(e) {
                                e.preventDefault();

                                addMaskLoading();

                                $.ajax({
                                    url: '{{ url("/logout") }}',
                                    method: 'GET',
                                    globalError: false
                                }).always(function() {
                                    window.location.reload();
                                });
                            });
                        });
                    </script>
                @endif

                <div class="text-center my-4 pt-4 pb-5">
                    <img src="{{ getSiteLogoUrl('dark') }}" alt="" width="300px">
                </div>

                {{-- <h1>{{ \Acelle\Model\Setting::get('site_name') }}</h1>
                <p>{{ \Acelle\Model\Setting::get('site_description') }}</p> --}}

				<!-- main inner content -->
				@include('plans._publicList', [
                    'plans' => $plans,
                ])

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

    {!! \Acelle\Model\Setting::get('custom_script') !!}

</body>
</html>

    
    