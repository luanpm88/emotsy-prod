@extends('layouts.backend')

@section('title', trans('messages.dashboard'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
	<h6 class="h1-subtitle">{{ trans('messages.admin') }}</h6>
    <h1 class="mt-0 mb-0">{{ trans('messages.dashboard') }}</h1>
	
	<div class="row admin-dashboard-container">
		<div class="col-md-7">
			<div class="row">
				@if (
					Auth::user()->can("read", new Acelle\Model\Subscription()) ||
					Auth::user()->can("read", new Acelle\Model\Customer())
				)
					<div class="col-md-6">
						<h2 class="">{{ trans('messages.customer') }}</h2>
						@if (Auth::user()->can("read", new Acelle\Model\Customer()))
							<a class="dashboard-box" href="{{ action('Admin\CustomerController@index') }}">								
								<div class="dashboard-icon">
									<i class="icon-users2"></i>
								</div>
								<h4>{{ trans('messages.customers') }}</h4>
							</a>
						@endif
						@if (Auth::user()->can("read", new Acelle\Model\Subscription()))
							<a class="dashboard-box" href="{{ action('Admin\SubscriptionController@index') }}">
								<span class="badge badge-danger">{{ Auth::user()->admin->subscriptionNotificationCount() }}</span>
								<div class="dashboard-icon">
									<i class="icon-quill4"></i>
								</div>
								<h4>{{ trans('messages.subscriptions') }}</h4>
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
						<h2 class="">{{ trans('messages.admin') }}</h2>						
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
								<h4>{{ trans('messages.admin_groups') }}</h4>
							</a>
						@endif
						
						<br style="clear:both" />
					</div>
				@endif		
				
			</div>
			
			<div class="row">				
				@if (
					Auth::user()->can("read", new Acelle\Model\Plan()) ||
					Auth::user()->can("read", new Acelle\Model\Currency()) ||					
					Auth::user()->can("read", new Acelle\Model\PaymentMethod())
				)
					<div class="col-md-12">
						<h2 class="">{{ trans('messages.plan') }}</h2>						
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
						@if (Auth::user()->can("read", new Acelle\Model\PaymentMethod()))
							<a class="dashboard-box" href="{{ action('Admin\PaymentMethodController@index') }}">
								<div class="dashboard-icon">
									<i class="icon-credit-card2"></i>
								</div>
								<h4>{{ trans('messages.payment_methods') }}</h4>
							</a>
						@endif
						<br style="clear:both" />
					</div>
				@endif
			</div>
			
			@if (
				Auth::user()->admin->getPermission("sending_domain_read") != 'no'
				|| Auth::user()->admin->getPermission("sending_server_read") != 'no'
				|| Auth::user()->admin->getPermission("bounce_handler_read") != 'no'
				|| Auth::user()->admin->getPermission("fbl_handler_read") != 'no'
			)
				<div class="row">
					<div class="col-md-12">
						<h2 class="">{{ trans('messages.sending') }}</h2>
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
			@endif
				
			<div class="row">
				<div class="col-md-12">
					<h2 class="">{{ trans('messages.setting') }}</h2>
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
						<h2 class="">{{ trans('messages.report') }}</h2>
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
			
			<h5 class="text-semibold"><i class="icon-users"></i> {{ trans('messages.customers_growth') }}</h5>
			@include('admin.customers._growth_chart')
			
			<h5 class="text-semibold"><i class="icon-clipboard2"></i> {{ trans('messages.plans_chart') }}</h5>
			@include('admin.plans._pie_chart')
		
			<div class="panel-group content-group-lg dashboard-accordion" id="accordion0">
				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a data-toggle="collapse" data-parent="#accordion0" href="#accordion-group0">
								<i class="icon-quill4"></i>
								{{ trans('messages.recent_subscriptions') }}
							</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group0" class="panel-collapse collapse in">
						<div class="panel-body pb-0">
							<ul class="modern-listing mt-0 mb-0 top-border-none">
								@foreach(Auth::user()->admin->recentSubscriptions() as $subscription)
									<li class="">
										<div class="row">
											<div class="col-sm-5 col-md-5">												
												<h6 class="mt-0 mb-0 text-semibold">													
													<a href="{{ action('Admin\CustomerController@subscriptions', $subscription->customer->uid) }}">
														<i class="icon-clipboard2"></i>
														{{ $subscription->plan_name }}
													</a>
												</h6>
												<p class="mb-0">
													<!--<img width="40" class="img-circle mr-10 pull-left" src="{{ action('CustomerController@avatar', $subscription->customer->uid) }}" alt="">-->
													<i class="icon-user" style="
														font-size: 14px;
														padding: 0;
														margin: 5px 0 0 -8px;
														height: auto;"></i>
													{{ $subscription->customer->displayName() }}
												</p>
											</div>
											<div class="col-sm-4 col-md-4 text-left">
												<h6 class="no-margin text-semibold">
													{{ Tool::formatDateTime($subscription->created_at) }}
												</h6>
												<span class="">{{ trans('messages.created_at') }}</span>
											</div>
											<div class="col-sm-3 col-md-3 text-left">
												<span class="text-muted2 list-status pull-left">
													<span class="label label-flat bg-{{ $subscription->status }}">{{ trans('messages.subscription_status_' . $subscription->status) }}</span>
												</span>
												<!--<br/>
												<span class="text-muted2 list-status pull-left">
													<span class="label label-sub label-flat bg-{{ $subscription->timeStatus() }}">{{ trans('messages.subscription_time_status_' . $subscription->timeStatus()) }}</span>
												</span>-->
											</div>	
										</div>
									</li>
								@endforeach
								@if (!Auth::user()->admin->recentSubscriptions()->count())
									<li class="text-center">
										{{ trans('messages.subscription_empty_line_1') }}
									</li>
								@endif
							</ul>
						</div>
					</div>
				</div>
				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a data-toggle="collapse" data-parent="#accordion1" href="#accordion-group1">
								<i class="icon-users"></i>
								{{ trans('messages.recent_customers') }}
							</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group1" class="panel-collapse collapse">
						<div class="panel-body pb-0">
							<ul class="modern-listing mt-0 mb-0 top-border-none">
								@foreach(Auth::user()->admin->recentCustomers() as $customer)
									<li class="">
										<div class="row">
											<div class="col-sm-5 col-md-5">
												<img width="40" class="img-circle mr-10 pull-left" src="{{ action('CustomerController@avatar', $customer->uid) }}" alt="">
												<h6 class="mt-0 mb-0 text-semibold">													
													<a href="{{ action('Admin\CustomerController@edit', $customer->uid) }}">
														{{ $customer->displayName() }}
													</a>
												</h6>
												<p class="mb-0">
													{{ $customer->admin->displayName() }}
												</p>
											</div>
											<div class="col-sm-4 col-md-4 text-left">
												<h6 class="no-margin text-semibold">
													{{ Tool::formatDateTime($customer->created_at) }}
												</h6>
												<span class="">{{ trans('messages.created_at') }}</span>
											</div>
											<div class="col-sm-3 col-md-3 text-left">
												<span class="text-muted2 list-status pull-left">
													<span class="label label-flat bg-{{ $customer->status }}">{{ trans('messages.subscription_status_' . $customer->status) }}</span>
												</span>
											</div>
										</div>
									</li>
								@endforeach
								@if (!Auth::user()->admin->recentCustomers()->count())
									<li class="text-center">
										{{ trans('messages.customer_empty_line_1') }}
									</li>
								@endif
							</ul>
						</div>
					</div>
				</div>
				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#accordion-group3">
								<i class="icon-history position-left"></i>
								{{ trans('messages.activities') }}
							</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group3" class="panel-collapse collapse">
						@if (\Auth::user()->admin->getLogs()->count() == 0)
							<div class="empty-list">
								<i class="icon-history"></i>
								<span class="line-1">
									{{ trans('messages.no_activity_logs') }}
								</span>
							</div>
						@else
							<div class="scrollbar-box action-log-box">
								<!-- Timeline -->
								<div class="timeline timeline-left content-group">
									<div class="timeline-container">				
											@foreach (\Auth::user()->admin->getLogs()->take(20)->get() as $log)
												<!-- Sales stats -->
												<div class="timeline-row">
													<div class="timeline-icon">
														<a href="#"><img src="{{ action('CustomerController@avatar', $log->customer->uid) }}" alt=""></a>
													</div>
						
													<div class="panel panel-flat timeline-content">
														<div class="panel-heading">
															<h6 class="panel-title text-semibold">{{ $log->customer->displayName() }}</h6>
															<div class="heading-elements">
																<span class="heading-text"><i class="icon-history position-left text-success"></i> {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</span>
															</div>
														</div>
						
														<div class="panel-body">
															{!! $log->message() !!}
														</div>
													</div>
												</div>
												<!-- /sales stats -->
											@endforeach								
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
		
    
@endsection
