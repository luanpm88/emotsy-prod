                    <ul class="nav nav-tabs nav-tabs-top">
						@if (Auth::user()->getOption("backend", "setting_general") == 'yes')
							<li class="{{ $action == "general" ? "active" : "" }} text-semibold">
                                <a href="{{ action('Admin\SettingController@general') }}">
								<i class="icon-equalizer2"></i> {{ trans('messages.general') }}</a></li>
						@endif
						@if (Auth::user()->getOption("backend", "setting_general") == 'yes')
							<li class="{{ $action == "mailer" ? "active" : "" }} text-semibold">
                                <a href="{{ action('Admin\SettingController@mailer') }}">
								<i class="icon-envelop"></i> {{ trans('messages.system_email') }}</a></li>
						@endif
						@if (Auth::user()->getOption("backend", "setting_sending") == 'yes')
							<li class="{{ $action == "sending" ? "active" : "" }} text-semibold">
                                <a href="{{ action('Admin\SettingController@sending') }}">
								<i class="icon-paperplane"></i> {{ trans('messages.sending') }}</a></li>
						@endif
						@if (Auth::user()->getOption("backend", "setting_system_urls") == 'yes')
							<li class="{{ $action == "urls" ? "active" : "" }} text-semibold">
                                <a href="{{ action('Admin\SettingController@urls') }}">
								<i class="icon-link"></i> {{ trans('messages.system_urls') }}</a></li>
						@endif
							<li class="{{ $action == "cronjob" ? "active" : "" }} text-semibold">
                                <a href="{{ action('Admin\SettingController@cronjob') }}">
								<i class="icon-alarm"></i> {{ trans('messages.cron_jobs') }}</a></li>
					</ul>