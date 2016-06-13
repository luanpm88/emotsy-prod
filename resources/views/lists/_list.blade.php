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
															custom-order="{{ $item->custom_order }}"
															name="ids[]"
															value="{{ $item->uid }}"
														/>
													</label>
												</div>
												@if (request()->sort_order == 'custom_order' && empty(request()->keyword))
													<i data-action="move" class="icon icon-more2 list-drag-button"></i>
												@endif
											</div>
										</td>
										<td>
											<h5 class="no-margin text-bold">
												<a href="{{ action('MailListController@edit', ['uid' => $item->uid]) }}">{{ $item->name }}</a>
											</h5>
											<span class="text-muted">{{ trans('messages.created_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
										</td>
										<td>
											<div class="single-stat-box pull-left">
												<span class="no-margin stat-num">{{ rand(0, 1000) }}</span>
												<br />
												<span class="text-muted">Subscribers</span>
											</div>
											<div class="single-stat-box pull-left">
												<span class="no-margin stat-num">{{ rand(0, 100) }}.0%</span>
												<br />
												<span class="text-muted">Opens</span>
											</div>
											<div class="single-stat-box pull-left">
												<span class="no-margin stat-num">{{ rand(0, 100) }}.0%</span>
												<br />
												<span class="text-muted">Clicks</span>
											</div>
											<br style="clear:both" />
										</td>
										<td class="text-right">
											<span class="text-muted2 list-status pull-left">
												<span class="label label-flat border-success-600 text-success-600">active</span>
											</span>
											<button data-popup="tooltip" title="Add subscriber" type="button" class="btn bg-teal btn-icon"><i class="icon icon-plus2"></i><i class="glyphicon glyphicon-user"></i></button>
											<button data-popup="tooltip" title="Overview" type="button" class="btn bg-grey btn-icon"><i class="icon-stats-growth"></i> <i class="icon-search4"></i></button>
											<div class="btn-group">										
												<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li><a href="#"><i class="icon-users4"></i> {{ trans("messages.subscribers") }}</a></li>
													<li><a href="#"><i class="icon-make-group"></i> {{ trans('messages.segments') }}</a></li>
													<li><a href="#"><i class="icon-certificate"></i> {{ trans('messages.custom_forms_and_emails') }}</a></li>
													<li><a href="#"><i class="icon-pencil7"></i> {{ trans("messages.edit_list") }}</a></li>
													<li><a href="#"><i class="icon-copy3"></i> {{ trans('messages.duplicate_list') }}</a></a></li>
													<li><a href="#"><i class="icon-download4"></i> {{ trans('messages.import') }}</a></li>
													<li><a href="#"><i class="icon-upload4"></i> {{ trans('messages.export') }}</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
							</table>
                            @include('elements/_per_page_select')
							{{ $lists->links() }}
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