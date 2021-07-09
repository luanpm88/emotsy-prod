@if ($lists->count() > 0)
	<table class="table table-box pml-table"
		current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
	>
		@foreach ($lists as $key => $item)
			<tr>
				<td width="1%">
					<div class="text-nowrap">
						<div class="checkbox inline">
							<label>
								<input type="checkbox" class="node styled"
									name="ids[]"
									value="{{ $item->uid }}"
								/>
							</label>
						</div>
					</div>
				</td>
				<td>
					<h5 class="m-0 text-bold">
						<a class="kq_search" href="{{ action('MailListController@overview', ['uid' => $item->uid]) }}">{{ $item->name }}</a>
					</h5>
					<span class="text-muted">{{ trans('messages.created_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
					@if (empty($item->getSendingServers()))
						<div class="text-danger"><span class="material-icons-outlined">
error_outline
</span> {{ trans('messages.list_has_no_sending_server') }}</div>
					@endif
				</td>
				<td class="stat-fix-size-sm">
					<div class="d-flex">
						<div class="single-stat-box pull-left">
							<a href="{{ action('SubscriberController@index', $item->uid) }}">
								<span class="no-margin stat-num">{{ number_with_delimiter($item->readCache('SubscriberCount', 0)) }}</span>
							</a>
							<br />
							<span class="text-muted">{{ trans("messages." . Acelle\Library\Tool::getPluralPrase('subscriber', $item->readCache('SubscriberCount', 0))) }}</span>
						</div>
						<div class="single-stat-box pull-left ml-20">
							<span class="no-margin text-teal-800 stat-num">{{ $item->openUniqRate() }}%</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ $item->readCache('UniqOpenRate', 0) }}%">
								</div>
							</div>
							<span class="text-muted">{{ trans('messages.open_rate') }}</span>
						</div>
						<div class="single-stat-box pull-left ml-20">
							<span class="no-margin text-teal-800 stat-num">{{ $item->readCache('ClickedRate', 0) }}%</span>
							<div class="progress progress-xxs">
								<div class="progress-bar progress-bar-info" style="width: {{ $item->readCache('ClickedRate', 0) }}%">
								</div>
							</div>
							<span class="text-muted">{{ trans('messages.click_rate') }}</span>
						</div>
					</div>
				</td>
				<td class="text-end">
					<div class="btn-group" role="group">
						<a href="{{ action('SubscriberController@create', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.create_subscriber') }}" role="button" class="btn btn-outline-secondary btn-icon">
							<span class="material-icons-outlined">
								person_add
							</span>
						</a>
						<a href="{{ action('MailListController@overview', $item->uid) }}" role="button" class="btn btn-outline-secondary">
							<span class="material-icons-outlined">
								multiline_chart
							</span> {{ trans('messages.list.overview_statistics') }}
						</a>
						<div class="btn-group" role="group">
							<button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary btn-icon dropdown-toggle ps-2"  data-bs-toggle="dropdown" aria-expanded="false">
									
							</button>
							<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="btnGroupDrop1">
								<li><a class="dropdown-item" href="{{ action('SubscriberController@index', $item->uid) }}"><i class="icon-users4"></i> {{ trans("messages.subscribers") }}</a></li>
								<li><a class="dropdown-item" href="{{ action('SegmentController@index', $item->uid) }}"><i class="icon-make-group"></i> {{ trans('messages.segments') }}</a></li>
								<li>
									<a class="dropdown-item" href="{{ action('MailListController@embeddedForm', $item->uid) }}">
										<i class="icon-embed2"></i> {{ trans('messages.Embedded_form') }}
									</a>
								</li>
								<li><a class="dropdown-item" href="{{ action('PageController@update', ['list_uid' => $item->uid, 'alias' => 'sign_up_form']) }}"><i class="icon-certificate"></i> {{ trans('messages.custom_forms_and_emails') }}</a></li>
								<li>
									<a class="dropdown-item" href="{{ action('FieldController@index', $item->uid) }}">
										<i class="icon-list3"></i> {{ trans('messages.manage_list_fields') }}
									</a>
								</li>
								<li><a class="dropdown-item" href="{{ action('MailListController@verification', $item->uid) }}"><i class="icon-envelop5"></i> {{ trans("messages.email_verification") }}</a></li>
								<li><a class="dropdown-item" href="{{ action('MailListController@edit', $item->uid) }}"><i class="icon-pencil7"></i> {{ trans("messages.edit_list") }}</a></li>
								@if (\Auth::user()->can('import', $item))
									<li><a class="dropdown-item" href="{{ action('SubscriberController@import', $item->uid) }}"><i class="icon-download4"></i> {{ trans('messages.import') }}</a></li>
								@endif
								@if (\Auth::user()->can('export', $item))
									<li><a class="dropdown-item" href="{{ action('SubscriberController@export', $item->uid) }}"><i class="icon-upload4"></i> {{ trans('messages.export') }}</a></li>
								@endif
								<li>
									<a class="dropdown-item" data-uid="{{ $item->uid }}" data-name="{{ trans("messages.copy_of_list", ['name' => $item->name]) }}" class="copy-list-link">
										<i class="icon-copy4"></i> {{ trans('messages.copy') }}
									</a>
								</li>
								@if ($settings['list.clone_for_others'])
									<li>
										<a class="dropdown-item"
											href="{{ action('MailListController@cloneForCustomersChoose', $item->uid) }}"
											class="clone-for-users"
										>
											<i class="icon-users"></i> {{ trans('messages.list.clone_for_other_users') }}
										</a>
									</li>
								@endif
								<li>
									<a class="dropdown-item" list-delete-confirm="{{ action('MailListController@deleteConfirm', ['uids' => $item->uid]) }}" href="{{ action('MailListController@delete', ['uids' => $item->uid]) }}">
										<i class="icon-trash"></i> {{ trans('messages.delete') }}
									</a>
								</li>
							</ul>
						</div>
					</div>
				</td>
			</tr>
		@endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $lists])
	{{ $lists->links() }}
	
	<script>
		var clonePopup = new Popup(); 
		$('.clone-for-users').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			
			clonePopup.load(url);
			
			//addMaskLoading();
			//
			//$.ajax({
			//	method: "POST",
			//	url: url,
			//	data: {
			//		_token: CSRF_TOKEN
			//	},
			//})
			//.done(function( msg ) {
			//	removeMaskLoading();
			//});
		});
	</script>
	
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<i class="icon-address-book2"></i>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<i class="icon-address-book2"></i>
		<span class="line-1">
			{{ trans('messages.list_empty_line_1') }}
		</span>
		<span class="line-2">
			{{ trans('messages.list_empty_line_2') }}
		</span>
	</div>
@endif
