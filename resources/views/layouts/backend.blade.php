<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

	@include('layouts._css')
	
	@include('layouts._js')
	
</head>

<body class="navbar-top">

	<!-- Main navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top">
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
				<li rel0="HomeController">
					<a href="{{ action('Admin\HomeController@index') }}">
						<i class="icon-home"></i> {{ trans('messages.dashboard') }}
					</a>
				</li>
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
                <li rel0="SendingServerController">
                    <a href="{{ action('Admin\SendingServerController@index') }}">
                        <i class="icon-server"></i> {{ trans('messages.sending_severs') }}
                    </a>
                </li>                
                <li
					rel0="BounceHandlerController"
					class="dropdown language-switch">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="glyphicon glyphicon-transfer"></i> {{ trans('messages.handler') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
                        <li rel0="BounceHandlerController">
                            <a href="{{ action('Admin\BounceHandlerController@index') }}">
                                <i class="glyphicon glyphicon-share"></i> {{ trans('messages.bounce_handler') }}
                            </a>
                        </li>
                        <li rel0="FeedbackLoopHandlerController">
                            <a href="{{ action('Admin\FeedbackLoopHandlerController@index') }}">
                                <i class="glyphicon glyphicon-retweet"></i> {{ trans('messages.feedback_loop_handler') }}
                            </a>
                        </li>
                    </ul>
				</li>				
				<li class="dropdown language-switch"
					rel0="TemplateController"
					rel1="LayoutController">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-gear"></i> {{ trans('messages.setting') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
                        <li rel0="TemplateController">
                            <a href="{{ action('Admin\TemplateController@index') }}">
                                <i class="icon-magazine"></i> {{ trans('messages.template_gallery') }}
                            </a>
                        </li>
						<li rel0="LayoutController">
                            <a href="{{ action('Admin\LayoutController@index') }}">
                                <i class="glyphicon glyphicon-file"></i> {{ trans('messages.page_form_layout') }}
                            </a>
                        </li>
						<li rel0="SendingDomainController">
							<a href="{{ action('Admin\SendingDomainController@index') }}">
								<i class="icon-earth"></i> {{ trans('messages.sending_domains') }}
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
				<!--<li class="dropdown language-switch">
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
                </li>-->

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ action('UserController@avatar', Auth::user()->uid) }}" alt="">
						<span>{{ Auth::user()->displayName() }}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						@if (Auth::user()->userGroup->frontend_access)
							<li><a href="{{ action("HomeController@index") }}"><i class="icon-exit"></i> {{ trans('messages.frontend') }}</a></li>
							<li class="divider"></li>
						@endif
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
