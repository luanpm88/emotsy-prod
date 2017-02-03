@extends('layouts.backend')

@section('title', trans('messages.dashboard'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
	<h6 class="h1-subtitle">{{ trans('messages.admin') }}</h6>
    <h1 class="mt-0">{{ trans('messages.dashboard') }}</h1>
	
	<div class="row">
		<div class="col-md-7">
			<div class="row">
				@if (
					Auth::user()->can("read", new Acelle\Model\Reseller()) ||
					Auth::user()->can("read", new Acelle\Model\ResellerGroup())
				)
					<div class="col-md-6">
						<h2 class="text-muted">{{ trans('messages.reseller') }}</h2>
						@if (Auth::user()->can("read", new Acelle\Model\Reseller()))
							<a class="dashboard-box" href="{{ action('Admin\ResellerController@index') }}">
								<span class="badge badge-info bg-teal-800">{{ Auth::user()->admin->resellers->count() }}</span>
								<div class="dashboard-icon">
									<i class="icon-users2"></i>
								</div>
								<h4>{{ trans('messages.resellers') }}</h4>
							</a>
						@endif
						@if (Auth::user()->can("read", new Acelle\Model\ResellerGroup()))
							<a class="dashboard-box" href="{{ action('Admin\ResellerGroupController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-make-group"></i>
								</div>
								<h4>{{ trans('messages.groups') }}</h4>
							</a>
						@endif						
						<br style="clear:both" />
					</div>
				@endif
				
				@if (
					Auth::user()->can("read", new Acelle\Model\Admin()) ||
					Auth::user()->can("read", new Acelle\Model\AdminGroup())
				)
					<div class="col-md-6">
						<h2 class="text-muted">{{ trans('messages.admin') }}</h2>						
						@if (Auth::user()->can("read", new Acelle\Model\Admin()))
							<a class="dashboard-box" href="{{ action('Admin\AdminController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-user-tie"></i>
								</div>
								<h4>{{ trans('messages.admins') }}</h4>
							</a>
						@endif
						@if (Auth::user()->can("read", new Acelle\Model\AdminGroup()))
							<a class="dashboard-box" href="{{ action('Admin\AdminGroupController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-make-group"></i>
								</div>
								<h4>{{ trans('messages.groups') }}</h4>
							</a>
						@endif
						
						<br style="clear:both" />
					</div>
				@endif		
				
			</div>
			<br />
			
			<div class="row">				
				@if (
					Auth::user()->can("read", new Acelle\Model\Plan()) ||
					Auth::user()->can("read", new Acelle\Model\Currency())
				)
					<div class="col-md-12">
						<h2 class="text-muted">{{ trans('messages.plan') }}</h2>
						@if (Auth::user()->can("read", new Acelle\Model\CustomerGroup()))
							<a class="dashboard-box" href="{{ action('Admin\CustomerGroupController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-make-group"></i>
								</div>
								<h4>{{ trans('messages.customer_groups') }}</h4>
							</a>
						@endif
						@if (Auth::user()->can("read", new Acelle\Model\Plan()))
							<a class="dashboard-box" href="{{ action('Admin\PlanController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-clipboard2"></i>
								</div>
								<h4>{{ trans('messages.plans') }}</h4>
							</a>
						@endif
						@if (Auth::user()->can("read", new Acelle\Model\Currency()))
							<a class="dashboard-box" href="{{ action('Admin\CurrencyController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-coins"></i>
								</div>
								<h4>{{ trans('messages.currencies') }}</h4>
							</a>
						@endif
						<br style="clear:both" />
					</div>
				@endif
			</div>
			<br />
			
			@if (
				Auth::user()->admin->getPermission("sending_domain_read") != 'no'
				|| Auth::user()->admin->getPermission("sending_server_read") != 'no'
				|| Auth::user()->admin->getPermission("bounce_handler_read") != 'no'
				|| Auth::user()->admin->getPermission("fbl_handler_read") != 'no'
			)
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-muted">{{ trans('messages.sending') }}</h2>
						@if (Auth::user()->admin->getPermission("sending_server_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\SendingServerController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-server"></i>
								</div>
								<h4>{{ trans('messages.sending_servers') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("bounce_handler_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\BounceHandlerController@index') }}">
								<div class="dashboard-icon">
									<i class="glyphicon glyphicon-share"></i>
								</div>
								<h4>{{ trans('messages.bounce_handlers') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("fbl_handler_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\FeedbackLoopHandlerController@index') }}">
								<div class="dashboard-icon">
									<i class="glyphicon glyphicon-retweet"></i>
								</div>
								<h4>{{ trans('messages.feedback_loop_handlers') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("sending_domain_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\SendingDomainController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-earth"></i>
								</div>
								<h4>{{ trans('messages.sending_domain') }}</h4>
							</a>
						@endif
						<br style="clear:both" />
					</div>
				</div>
				<br />
			@endif
			
			
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-muted">{{ trans('messages.setting') }}</h2>
						<a class="dashboard-box" href="{{ action('Admin\SettingController@index') }}">
							<div class="dashboard-icon">
								<i class="icon-equalizer2"></i>
							</div>
							<h4>{{ trans('messages.all_settings') }}</h4>
						</a>
						@if (Auth::user()->admin->getPermission("template_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\TemplateController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-magazine"></i>
								</div>
								<h4>{{ trans('messages.template_gallery') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("layout_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\LayoutController@index') }}">
								<div class="dashboard-icon">
									<i class="glyphicon glyphicon-file"></i>
								</div>
								<h4>{{ trans('messages.page_form_layout') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("language_read") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\LanguageController@index') }}">
								<div class="dashboard-icon">
									<i class="glyphicon glyphicon-flag"></i>
								</div>
								<h4>{{ trans('messages.languages') }}</h4>
							</a>
						@endif
						<br style="clear:both" />
					</div>
				</div>
				<br />
			
			@if (
				Auth::user()->admin->getPermission("report_blacklist") != 'no'
				|| Auth::user()->admin->getPermission("report_tracking_log") != 'no'
				|| Auth::user()->admin->getPermission("report_bounce_log") != 'no'
				|| Auth::user()->admin->getPermission("report_feedback_log") != 'no'
				|| Auth::user()->admin->getPermission("report_open_log") != 'no'
				|| Auth::user()->admin->getPermission("report_click_log") != 'no'
				|| Auth::user()->admin->getPermission("report_unsubscribe_log") != 'no'
			)
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-muted">{{ trans('messages.report') }}</h2>
						@if (Auth::user()->admin->getPermission("report_blacklist") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\BlacklistController@index') }}">
								<div class="dashboard-icon">
									<i class="glyphicon glyphicon-minus-sign"></i>
								</div>
								<h4>{{ trans('messages.blacklist') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("report_tracking_log") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\TrackingLogController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-file-text2"></i>
								</div>
								<h4>{{ trans('messages.tracking') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("report_bounce_log") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\BounceLogController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-file-text2"></i>
								</div>
								<h4>{{ trans('messages.bounce') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("report_feedback_log") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\FeedbackLogController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-file-text2"></i>
								</div>
								<h4>{{ trans('messages.feedback') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("report_open_log") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\OpenLogController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-file-text2"></i>
								</div>
								<h4>{{ trans('messages.open') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("report_click_log") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\ClickLogController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-file-text2"></i>
								</div>
								<h4>{{ trans('messages.click') }}</h4>
							</a>
						@endif
						@if (Auth::user()->admin->getPermission("report_unsubscribe_log") != 'no')
							<a class="dashboard-box" href="{{ action('Admin\UnsubscribeLogController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-file-text2"></i>
								</div>
								<h4>{{ trans('messages.unsubscribe') }}</h4>
							</a>
						@endif
						<br style="clear:both" />
					</div>
				</div>
			@endif
		</div>
		<div class="col-md-5">
			<div class="panel-group content-group-lg" id="accordion1">
				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a data-toggle="collapse" data-parent="#accordion1" href="#accordion-group1">{{ trans('messages.recent_resellers') }}</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group1" class="panel-collapse collapse in">
						<div class="panel-body">
							<ul class="modern-listing mt-0 mb-0 top-border-none">
								@foreach(Auth::user()->admin->recentResellers() as $item)
									<li class="">
										<div class="row">
											<div class="col-sm-5 col-md-5">
												<img width="40" class="img-circle mr-10 pull-left" src="{{ action('ResellerController@avatar', $item->uid) }}" alt="">
												<h6 class="mt-0 mb-0 text-semibold">													
													<a href="{{ action('Admin\ResellerController@edit', $item->uid) }}">
														{{ $item->displayName() }}
													</a>
												</h6>
												<p>
													{{ $item->resellerGroup->name }}
												</p>
											</div>
											<div class="col-sm-3 col-md-3 text-left">
												<h6 class="no-margin text-bold">
													{{ $item->customers->count() }}
												</h6>
												<span class="text-muted">{{ trans('messages.customers') }}</span>
													<br><br>
											</div>
											<div class="col-sm-4 col-md-4 text-left">
												<h6 class="no-margin text-semibold">
													{{ Tool::formatDateTime($item->created_at) }}
												</h6>
												<span class="text-muted">{{ trans('messages.created_at') }}</span>
											</div>	
										</div>
			
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#accordion-group2">{{ trans('messages.recent_customers') }}</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group2" class="panel-collapse collapse">
						<div class="panel-body">
							Тon cupidatat skateboard dolor brunch. Тesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda.
						</div>
					</div>
				</div>

				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#accordion-group3">{{ trans('messages.activities') }}</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group3" class="panel-collapse collapse">
						<div class="panel-body">
							3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
    
@endsection
