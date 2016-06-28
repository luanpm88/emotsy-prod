<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Source+Code+Pro:400,600|Open+Sans:300italic,400italic,600italic,700italic,700,300,600,400" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('assets/css/components.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('css/app.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/loaders/pace.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/ui/nicescroll.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/ui/drilldown.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/validation/validate.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/notifications/bootbox.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/switch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/jquery.numeric.min.js') }}"></script>
	
	@yield('page_script')
	
    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
	<!-- /theme JS files -->
	
	@include('layouts._script_vars')
	@include('layouts._menu_script')
	
	<!-- display flash message -->
	@include('common.flash')
</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ action('HomeController@index') }}">
				<img src="{{ URL::asset('assets/images/logo_light.png') }}" alt="">
			</a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a class="mobile-menu-button" data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-menu7"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				
                <li class="dropdown language-switch"
					rel0="UserGroupController"
					rel1="UserController"
				>
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user"></i> {{ trans('messages.user') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
                        <li rel0="UserController">
                            <a href="{{ action('Admin\UserController@index') }}">
                                <i class="icon-users"></i> {{ trans('messages.users') }}
                            </a>
                        </li>
                        <li rel0="UserGroupController">
                            <a href="{{ action('Admin\UserGroupController@index') }}">
                                <i class="icon-users4"></i> {{ trans('messages.groups') }}
                            </a>
                        </li>
                    </ul>
				</li>
                <li rel0="">
                    <a href="">
                        <i class="icon-server"></i> {{ trans('messages.sending_severs') }}
                    </a>
                </li>                
                <li class="dropdown language-switch" rel0="">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="glyphicon glyphicon-transfer"></i> {{ trans('messages.handler') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="">
                                <i class="glyphicon glyphicon-share"></i> {{ trans('messages.bounce_handler') }}
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="glyphicon glyphicon-retweet"></i> {{ trans('messages.feedbackloop_handler') }}
                            </a>
                        </li>
                    </ul>
				</li>
				<li rel0="">
                    <a href="">
                        <i class="icon-earth"></i> {{ trans('messages.sending_domains') }}
                    </a>
                </li>
				<li class="dropdown language-switch" rel0="">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-gear"></i> {{ trans('messages.setting') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="">
                                <i class="icon-magazine"></i> {{ trans('messages.template') }}
                            </a>
                        </li>
						<li>
                            <a href="">
                                <i class="glyphicon glyphicon-file"></i> {{ trans('messages.page_form_layout') }}
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="glyphicon glyphicon-flag"></i> {{ trans('messages.language') }}
                            </a>
                        </li>
                    </ul>
				</li>
				<li class="dropdown language-switch" rel0="">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-file-text2"></i> {{ trans('messages.report') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="">
                                <i class="glyphicon glyphicon-minus-sign"></i> {{ trans('messages.blacklist') }}
                            </a>
                        </li>
						<li>
                            <a href="">
                                <i class="icon-file-text2"></i> {{ trans('messages.sending_log') }}
                            </a>
                        </li>
						<li>
                            <a href="">
                                <i class="icon-file-text2"></i> {{ trans('messages.bounce_log') }}
                            </a>
                        </li>
						<li>
                            <a href="">
                                <i class="icon-file-text2"></i> {{ trans('messages.feedback_log') }}
                            </a>
                        </li>
                    </ul>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown language-switch">
					<a class="dropdown-toggle" data-toggle="dropdown">
						{{ Mailixa\Model\Language::getByCode(Config::get('app.locale'))->name }}
						<span class="caret"></span>
					</a>

					<ul class="dropdown-menu">
						@foreach(Mailixa\Model\Language::getAll() as $language)
							<li class="{{ Mailixa\Model\Language::getByCode(Config::get('app.locale'))->code == $language->code ? "active" : "" }}">
								<a>{{ $language->name }}</a>
							</li>
						@endforeach
					</ul>
                </li>

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ action('UserController@avatar', Auth::user()->uid) }}" alt="">
						<span>{{ Auth::user()->displayName() }}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ action("AccountController@profile") }}"><i class="icon-profile"></i> {{ trans('messages.profile') }}</a></li>
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
	
	<a style="display: none" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#delete_confirm_model">aa</a>
	<!-- Basic modal -->
	<div id="delete_confirm_model" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="confirm-delete-form" onkeypress="return event.keyCode != 13;">
					<div class="modal-header bg-danger">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title">{{ trans('messages.are_you_sure') }}</h2>
					</div>
	
					<div class="modal-body">
						
							<h6></h6>
							
							<div class="form-group">
								<label>{!! trans('messages.type_delete_to_confirm') !!}</label>
								<input class="form-control" name="delete" />
							</div>
						
					</div>
	
					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.cancel') }}</button>
						<a class="btn btn-danger delete-confirm-button ajax_link">{{ trans('messages.delete') }}</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /basic modal -->
	
	<a style="display: none" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#link_confirm_model">Open modal</a>
	<!-- Basic modal -->
	<div id="link_confirm_model" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="confirm-link-form" onkeypress="return event.keyCode != 13;">
					<div class="modal-header bg-info-800">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title">{{ trans('messages.are_you_sure') }}</h2>
					</div>
	
					<div class="modal-body">
						
							<h6></h6>
						
					</div>
	
					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.cancel') }}</button>
						<a class="btn bg-info-800 link-confirm-button ajax_link">{{ trans('messages.confirm') }}</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /basic modal -->
	
	<a style="display: none" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#list_delete_confirm_model">aa</a>
	<!-- Basic modal -->
	<div id="list_delete_confirm_model" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="list-confirm-delete-form" onkeypress="return event.keyCode != 13;">
					<div class="modal-header bg-danger">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title">{{ trans('messages.are_you_sure') }}</h2>
					</div>
	
					<div class="modal-body">
						
							<div class="content">
								
							</div>
							
							<div class="form-group">
								<label>{!! trans('messages.type_delete_to_confirm') !!}</label>
								<input class="form-control" name="delete" />
							</div>
						
					</div>
	
					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.cancel') }}</button>
						<a class="btn btn-danger list-delete-confirm-button ajax_link">{{ trans('messages.delete') }}</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /basic modal -->
</body>
</html>
