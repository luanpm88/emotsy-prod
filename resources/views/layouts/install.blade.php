<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') - Mailixa</title>
		
	@include('layouts._head')

	@include('layouts._css')
	
	@include('layouts._js')

</head>

<body class="bg-slate-800">

	<!-- Page container -->
	<div class="page-container login-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
				<div class="row">
					<div class="col-sm-2 col-md-2">
						
					</div>
					<div class="col-sm-8 col-md-8">
					
						<div class="text-center login-header">
							<a class="main-logo-big" href="{{ action('HomeController@index') }}">
								<img src="{{ URL::asset('images/logo_big.png') }}" alt="">
							</a>
                            
                            <h4 class="text-center text-muted2">{{ trans('messages.installation') }}</h4>
						</div>
                        
                        @include('install._steps')
                        
                        <div class="panel panel-flat" style="border-radius: 0 0 3px 3px">
                            <div class="panel-body">
                                @yield('content')
                            </div>
						</div>
					</div>
				</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->


		<!-- Footer -->
		<div class="footer text-white">
			{!! trans('messages.copy_right_light') !!}			
		</div>
		<!-- /footer -->

	</div>
	<!-- /page container -->

</body>
</html>
