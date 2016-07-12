                <ul class="nav nav-pills campaign-steps install-steps">					
                    <li class="{{ $current == 1 ? "active" : "" }}">
						<a href="{{ action("InstallController@requirements") }}">
							<i class="icon-server"></i> {{ trans('messages.requirement') }}
						</a>
					</li>
                    <li class="{{ $current == 2 ? "active" : "" }}">
						<a href="{{ action("InstallController@permissions") }}">
							<i class="icon-file-check"></i> {{ trans('messages.permission') }}
						</a>
					</li>
                    <li class="{{ $current == 3 ? "active" : "" }}">
						<a href="{{ action("InstallController@siteInfo") }}">
							<i class="icon-home9"></i> {{ trans('messages.site_info') }}
						</a>
					</li>
					<li class="{{ $current == 4 ? "active" : "" }}">
						<a href="{{ action("InstallController@database") }}">
							<i class="icon-database"></i> {{ trans('messages.database') }}
						</a>
					</li>                    
				</ul>