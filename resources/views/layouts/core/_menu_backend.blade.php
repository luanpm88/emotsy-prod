<nav class="navbar navbar-expand-xl navbar-dark fixed-top navbar-main py-0">
    <div class="container-fluid ms-0">
        <a class="navbar-brand d-flex align-items-center me-2" href="{{ action('HomeController@index') }}">
            @if (\Acelle\Model\Setting::get('site_logo_small'))
                <img class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
            @else
                <img class="logo" src="{{ URL::asset('images/logo-' . (Auth::user()->admin->getColorScheme() == "white" ? "dark" : "light") . '.svg') }}" alt="">
            @endif
        </a>
        <button class="navbar-toggler" role="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        @php			
            $iconDir = "";
            if (Auth::user()->admin->getColorScheme() == 'white') {
                $iconDir = "dark/";
            }
        @endphp

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-md-0">
                <li class="nav-item" rel0="HomeController">
					<a href="{{ action('Admin\HomeController@index') }}" class="nav-link lvl-1 d-flex align-items-center">
						<i class="acelle-icon mr-3">
							<img src="{{ url('images/icons/'.$iconDir.'SVG/home.svg') }}" />
						</i>
						{{ trans('messages.dashboard') }}
					</a>
				</li>

				@if (Auth::user()->can("read", new Acelle\Model\Customer()))
					<li class="nav-item dropdown language-switch"
						rel0="CustomerGroupController"
						rel1="CustomerController"
					>
						<a role="button" class="nav-link lvl-1 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
							<i class="acelle-icon mr-3" style="width: 19px;">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/customer.svg') }}" />
							</i>
							{{ trans('messages.customer') }}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->can("read", new Acelle\Model\Customer()))
								<li rel0="CustomerController">
									<a href="{{ action('Admin\CustomerController@index') }}" class="dropdown-item d-flex align-items-center">
										<i class="acelle-icon mr-3">
											<img src="{{ url('images/icons/SVG/customers.svg') }}" />
										</i>
										{{ trans('messages.customers') }}
									</a>
								</li>
							@endif
							<li rel0="SubscriptionController">
								<a href="{{ action('Admin\SubscriptionController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
										<img src="{{ url('images/icons/SVG/subscription.svg') }}" />
									</i>
									{{ trans('messages.subscriptions') }}
								</a>
							</li>
						</ul>
					</li>
				@endif

				@if (
					Auth::user()->can("read", new Acelle\Model\Plan())
					|| Auth::user()->can("read", new Acelle\Model\Currency())
				)
					<li class="nav-item dropdown language-switch"
						rel0="PlanController"
						rel1="CurrencyGroupController"
					>
						<a role="button" class="nav-link lvl-1 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
							<i class="acelle-icon mr-3" style="width: 23px;">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/plan.svg') }}" />
							</i>{{ trans('messages.plan') }}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->can("read", new Acelle\Model\Plan()))
								<li rel0="PlanController">
									<a href="{{ action('Admin\PlanController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3" style="width: 20px;">
										<img src="{{ url('images/icons/SVG/plans.svg') }}" />
									</i>{{ trans('messages.plans') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->can("read", new Acelle\Model\Currency()))
								<li rel0="CurrencyController">
									<a href="{{ action('Admin\CurrencyController@index') }}" class="dropdown-item d-flex align-items-center">
										<i class="acelle-icon mr-3" style="width: 20px;">
											<img src="{{ url('images/icons/SVG/currency.svg') }}" />
										</i>{{ trans('messages.currencies') }}
									</a>
								</li>
							@endif
						</ul>
					</li>
				@endif

				@if (
					Auth::user()->admin->getPermission("admin_read") != 'no'
					|| Auth::user()->admin->getPermission("admin_group_read") != 'no'
				)
					<li class="nav-item dropdown language-switch"
						rel0="AdminGroupController"
						rel1="AdminController"
					>
						<a role="button" class="nav-link lvl-1 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
						<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/admin.svg') }}" />
							</i>{{ trans('messages.admin') }}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->admin->getPermission("admin_read") != 'no')
								<li rel0="AdminController">
									<a href="{{ action('Admin\AdminController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/admins.svg') }}" />
							</i>{{ trans('messages.admins') }}										
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("admin_group_read") != 'no')
								<li rel0="AdminGroupController">
									<a href="{{ action('Admin\AdminGroupController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/group.svg') }}" />
							</i>{{ trans('messages.admin_groups') }}
									</a>
								</li>
							@endif
						</ul>
					</li>
				@endif

				@if (
					Auth::user()->admin->getPermission("sending_domain_read") != 'no'
					|| Auth::user()->admin->getPermission("sending_server_read") != 'no'
					|| Auth::user()->admin->getPermission("bounce_handler_read") != 'no'
					|| Auth::user()->admin->getPermission("fbl_handler_read") != 'no'
					|| Auth::user()->admin->getPermission("email_verification_server_read") != 'no'
					|| Auth::user()->admin->can('read', new \Acelle\Model\SubAccount())
				)
					<li class="nav-item dropdown language-switch"
						rel0="BounceHandlerController"
						rel1="FeedbackLoopHandlerController"
						rel2="SendingServerController"
						rel3="SendingDomainController"
						rel4="SubAccountController"
						rel5="EmailVerificationServerController"
					>
						<a role="button" class="nav-link lvl-1 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
						<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/sending.svg') }}" />
							</i> {{ trans('messages.sending') }}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->admin->getPermission("sending_server_read") != 'no')
								<li rel0="SendingServerController">
									<a href="{{ action('Admin\SendingServerController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/server.svg') }}" />
							</i>{{ trans('messages.sending_severs') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->can('read', new \Acelle\Model\SubAccount()))
								<li rel0="SubAccountController">
									<a href="{{ action('Admin\SubAccountController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/account.svg') }}" />
							</i>{{ trans('messages.sub_accounts') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("bounce_handler_read") != 'no')
								<li rel0="BounceHandlerController">
									<a href="{{ action('Admin\BounceHandlerController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/bounce.svg') }}" />
							</i>{{ trans('messages.bounce_handlers') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("fbl_handler_read") != 'no')
								<li rel0="FeedbackLoopHandlerController">
									<a href="{{ action('Admin\FeedbackLoopHandlerController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/feedback.svg') }}" />
							</i>{{ trans('messages.feedback_loop_handlers') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("email_verification_server_read") != 'no')
								<li rel0="EmailVerificationServerController">
									<a href="{{ action('Admin\EmailVerificationServerController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/vserver.svg') }}" />
							</i>{{ trans('messages.email_verification_servers') }}
									</a>
								</li>
							@endif							
						</ul>
					</li>
				@endif
				<li class="nav-item dropdown language-switch"
					rel0="TemplateController"
					rel1="LayoutController"
					rel2="LanguageController"
					rel3="SettingController"
					rel4="PaymentController"
					rel5="PluginController"
				>
					<a role="button" class="nav-link lvl-1 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
					<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/setting.svg') }}" />
							</i>{{ trans('messages.setting') }}
                        <span class="caret"></span>
					</a>
                    <ul class="dropdown-menu">
						@if (
							Auth::user()->admin->getPermission("setting_general") != 'no' ||
							Auth::user()->admin->getPermission("setting_sending") != 'no' ||
							Auth::user()->admin->getPermission("setting_system_urls") != 'no' ||
							Auth::user()->admin->getPermission("setting_background_job") != 'no'
						)
							<li rel0="SettingController">
								<a href="{{ action('Admin\SettingController@index') }}" class="dropdown-item d-flex align-items-center">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/setting2.svg') }}" />
							</i>{{ trans('messages.all_settings') }}
								</a>
							</li>
						@endif
						@if (Auth::user()->admin->getPermission("template_read") != 'no')
							<li rel0="TemplateController">
								<a href="{{ action('Admin\TemplateController@index') }}" class="dropdown-item d-flex align-items-center">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/dtemplate.svg') }}" />
							</i>{{ trans('messages.template_gallery') }}
								</a>
							</li>
						@endif
						@if (Auth::user()->admin->getPermission("layout_read") != 'no')
							<li rel0="LayoutController">
								<a href="{{ action('Admin\LayoutController@index') }}" class="dropdown-item d-flex align-items-center">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/page.svg') }}" />
							</i>{{ trans('messages.page_form_layout') }}
								</a>
							</li>
						@endif
						@if (Auth::user()->admin->getPermission("language_read") != 'no')
							<li rel0="LanguageController">
								<a href="{{ action('Admin\LanguageController@index') }}" class="dropdown-item d-flex align-items-center">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/lang.svg') }}" />
							</i>{{ trans('messages.language') }}
								</a>
							</li>
						@endif
						@if (Auth::user()->admin->getPermission("payment_method_read") != 'no')
							<li rel0="PaymentController">
								<a href="{{ action('Admin\PaymentController@index') }}" class="dropdown-item d-flex align-items-center">
								<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/card.svg') }}" />
							</i>{{ trans('messages.payment_gateways') }}
								</a>
							</li>
						@endif
						<li rel0="PluginController">
							<a href="{{ action('Admin\PluginController@index') }}" class="dropdown-item d-flex align-items-center">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/plugin.svg') }}" />
							</i>{{ trans('messages.plugins') }}
							</a>
						</li>
                    </ul>
				</li>

				@if (
					Auth::user()->admin->getPermission("report_blacklist") != 'no'
					|| Auth::user()->admin->getPermission("report_tracking_log") != 'no'
					|| Auth::user()->admin->getPermission("report_bounce_log") != 'no'
					|| Auth::user()->admin->getPermission("report_feedback_log") != 'no'
					|| Auth::user()->admin->getPermission("report_open_log") != 'no'
					|| Auth::user()->admin->getPermission("report_click_log") != 'no'
					|| Auth::user()->admin->getPermission("report_unsubscribe_log") != 'no'
				)
					<li class="nav-item dropdown language-switch"
						rel0="TrackingLogController"
						rel1="OpenLogController"
						rel2="ClickLogController"
						rel3="FeedbackLogController"
						rel4="BlacklistController"
						rel5="UnsubscribeLogController"
						rel6="BounceLogController"
					>
						<a role="button" class="nav-link lvl-1 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
							<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/report.svg') }}" />
							</i>{{ trans('messages.report') }}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							@if (Auth::user()->admin->getPermission("report_blacklist") != 'no')
								<li rel0="BlacklistController">
									<a href="{{ action('Admin\BlacklistController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/blacklist.svg') }}" />
							</i>{{ trans('messages.blacklist') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("report_tracking_log") != 'no')
								<li rel0="TrackingLogController">
									<a href="{{ action('Admin\TrackingLogController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/sreport.svg') }}" />
							</i>{{ trans('messages.tracking_log') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("report_bounce_log") != 'no')
								<li rel0="BounceLogController">
									<a href="{{ action('Admin\BounceLogController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/bounce.svg') }}" />
							</i>{{ trans('messages.bounce_log') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("report_feedback_log") != 'no')
								<li rel0="FeedbackLogController">
									<a href="{{ action('Admin\FeedbackLogController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/feedback.svg') }}" />
							</i>{{ trans('messages.feedback_log') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("report_open_log") != 'no')
								<li rel0="OpenLogController">
									<a href="{{ action('Admin\OpenLogController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/open.svg') }}" />
							</i>{{ trans('messages.open_log') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("report_click_log") != 'no')
								<li rel0="ClickLogController">
									<a href="{{ action('Admin\ClickLogController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/click.svg') }}" />
							</i>{{ trans('messages.click_log') }}
									</a>
								</li>
							@endif
							@if (Auth::user()->admin->getPermission("report_unsubscribe_log") != 'no')
								<li rel0="UnsubscribeLogController">
									<a href="{{ action('Admin\UnsubscribeLogController@index') }}" class="dropdown-item d-flex align-items-center">
									<i class="acelle-icon mr-3">
								<img src="{{ url('images/icons/'.$iconDir.'SVG/unsubscribe.svg') }}" />
							</i>{{ trans('messages.unsubscribe_log') }}
									</a>
								</li>
							@endif
						</ul>
					</li>
				@endif
            </ul>
            <div class="navbar-right">
                <ul class="navbar-nav me-auto mb-md-0">
                    @include('layouts.core._top_notifications')
                    
                    @include('layouts.core._menu_backend_user')
                </ul>
            </div>
        </div>
    </div>
</nav>