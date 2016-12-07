                        @if ($automations->count() > 0)
							<table class="table table-box pml-table"
                                current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
                            >
								@foreach ($automations as $key => $automation)
									<tr>
										<td width="1%">
											<div class="text-nowrap">
												<div class="checkbox inline">
													<label>
														<input type="checkbox" class="node styled"
															custom-order="{{ $automation->custom_order }}"
															name="ids[]"
															value="{{ $automation->uid }}"
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
												<a class="kq_search" href="{{ action('AutomationController@edit', $automation->uid) }}">
													{{ $automation->name }}
												</a>
											</h5>
											@if ($automation->getInitEvent()->displayMessage())
												<span class="text-muted">{!! $automation->getInitEvent()->displayMessage() !!}</span>
												<br />
											@endif
											@if ($automation->subscribers()->count())												
												@if (is_object($automation->segment))
													<span class="text-semibold">{{ $automation->subscribers()->count() }}</span> <span class="text-muted">{{ strtolower(trans('messages.subscribers')) }} ({{ $automation->mailList->name }} . {{ $automation->segment->name }})</span>
												@elseif (is_object($automation->mailList))
													<span class="text-semibold">{{ $automation->subscribers()->count() }}</span> <span class="text-muted">{{ strtolower(trans('messages.subscribers')) }} ({{ $automation->mailList->name }})</span>
												@endif
											@endif
										</td>
										<td>
											<h5 class="no-margin">
												{{ trans('messages.auto_event_' . $automation->getInitEvent()->event_type) }}
											</h5>
											<span class="text-muted2">{{ trans('messages.type') }}</span>
										</td>
										<td class="text-center">
											<span class="text-muted2 list-status">
												<span class="label label-flat bg-{{ $automation->status }}">{{ $automation->status }}</span>
											</span>
										</td>
										<td class="text-right">
											@if (\Gate::allows('update', $automation))
												<a href="{{ action('AutomationController@edit', $automation->uid) }}" type="button" class="btn bg-grey btn-icon"> <i class="icon-pencil"></i> {{ trans('messages.edit') }}</a>
											@endif
											@if (\Gate::allows('delete', $automation))
												<div class="btn-group">										
													<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret ml-0"></span></button>
													<ul class="dropdown-menu dropdown-menu-right">
														@if (\Gate::allows('delete', $automation))
															<li><a data-method='delete' delete-confirm="{{ trans('messages.delete_automations_confirm') }}" href="{{ action('AutomationController@delete', ["uids" => $automation->uid]) }}"><i class="icon-trash"></i> {{ trans("messages.delete") }}</a></li>
														@endif
													</ul>
												</div>
											@endif
										</td>
									</tr>
								@endforeach
							</table>
                            @include('elements/_per_page_select', ["items" => $automations])
							{{ $automations->links() }}
						@elseif (!empty(request()->keyword))
							<div class="empty-list">
								<i class="icon-paperplane"></i>
								<span class="line-1">
									{{ trans('messages.no_search_result') }}
								</span>
							</div>
						@else					
							<div class="empty-list">
								<i class="icon-alarm-check"></i>
								<span class="line-1">
									{{ trans('messages.automation_empty_line_1') }}
								</span>
							</div>
						@endif