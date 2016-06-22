                <ul class="nav nav-pills campaign-steps">
					<li class="{{ $current == 1 ? "active" : "" }}">
						<a href="">
							<i class="icon-users4"></i> {{ trans('messages.recipients') }}
						</a>
					</li>
					<li class="{{ $current == 2 ? "active" : "" }} {{ $campaign->step() > 0 ? "" : "disabled" }}">
						<a href="">
							<i class="icon-gear"></i> {{ trans('messages.setup') }}
						</a>
					</li>
					<li class="{{ $current == 3 ? "active" : "" }} {{ $campaign->step() > 1 ? "" : "disabled" }}">
						<a href="">
							<i class="icon-magazine"></i> {{ trans('messages.templates') }}
						</a>
					</li>
					<li class="{{ $current == 4 ? "active" : "" }} {{ $campaign->step() > 2 ? "" : "disabled" }}">
						<a href="">
							<i class="icon-checkmark4"></i> {{ trans('messages.finish') }}
						</a>
					</li>
				</ul>