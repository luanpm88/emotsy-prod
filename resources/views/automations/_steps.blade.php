                <ul class="nav nav-pills campaign-steps">
					<li class="{{ $step == "recipients" ? "active" : "" }}">
						<a href="{{ action('AutomationController@edit', $automation->uid) }}">
							<i class="icon-users4"></i> {{ trans('messages.Recipients') }}
						</a>
					</li>
					<li class="{{ $step == "trigger" ? "active" : "" }}">
						<a href="{{ action('AutomationController@trigger', $automation->uid) }}">
							<i class="icon-alignment-align"></i> {{ trans('messages.Trigger') }}
						</a>
					</li>
				</ul>