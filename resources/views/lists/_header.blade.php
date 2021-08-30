<div class="page-title">
	<ul class="breadcrumb breadcrumb-caret position-right">
		<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		<li class="breadcrumb-item"><a href="{{ action("MailListController@index") }}">{{ trans('messages.lists') }}</a></li>
		<li class="breadcrumb-item">
			<div class="btn-group other-lists" style="margin-top: -4px;">
				<button role="button" class="btn dropdown-toggle text-teal-600 change-list-button" data-bs-toggle="dropdown">{{ trans('messages.change_list') }} <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-left">
					@forelse ($list->otherLists() as $l)
						<li>
							<a class="dropdown-item" href="{{ action('MailListController@overview', ['uid' => $l->uid]) }}">
								{{ $l->readCache('LongName', $l->name) }}
							</a>
						</li>
					@empty
						<li><a href="#">({{ trans('messages.empty') }})</a></li>
					@endforelse
				</ul>
			</div>
		</li>
	</ul>

	<h1>
		<span class="text-semibold">{{ $list->name }}</span>
	</h1>
	<span class="badge badge-info bg-info-800 badge-big">{{ number_with_delimiter($list->readCache('SubscriberCount')) }}</span> {{ trans('messages.subscribers') }}
</div>
