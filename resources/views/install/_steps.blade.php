                <ul class="nav nav-pills campaign-steps install-steps">					
                    <li class="{{ $current == 1 ? "active" : "" }} {{ $step >= 0 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@systemCompatibility") }}">
							<i class="icon-server"></i> {{ trans('messages.system_compatibility') }}
						</a>
					</li>
                    <li class="{{ $current == 2 ? "active" : "" }} {{ $step >= 1 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@siteInfo") }}">
							<span class="material-icons-outlined">
settings
</span> {{ trans('messages.configuration') }}
						</a>
					</li>
					<li class="{{ $current == 3 ? "active" : "" }} {{ $step >= 2 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@database") }}">
							<i class="icon-database"></i> {{ trans('messages.database') }}
						</a>
					</li>
					<li class="{{ $current == 5 ? "active" : "" }} {{ $step >= 4 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@cronJobs") }}">
							<i class="icon-alarm"></i> {{ trans('messages.background_job') }}
						</a>
					</li>
					<li class="{{ $current == 6 ? "active" : "" }} {{ $step >= 5 ? "enabled" : "" }}">
						<a href="{{ action("InstallController@finish") }}">
							<span class="material-icons-outlined">
task_alt
</span> {{ trans('messages.finish') }}
						</a>
					</li>
				</ul>