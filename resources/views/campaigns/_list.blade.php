                        @if ($campaigns->count() > 0)
							<table class="table table-box pml-table"
                                current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
                            >
								@foreach ($campaigns as $key => $item)
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
												<a class="kq_search" href="{{ action('CampaignController@show', $item->uid) }}">
													{{ $item->name }}
												</a>
											</h5>
											<span class="text-muted">{{ trans('messages.' . $item->type) }}</span>
											<br />
											<span class="text-muted2">{{ trans('messages.updated_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
										</td>
										<td>
											@if (is_object($item->segment))
												<span class="no-margin stat-num">{{ $item->mailList->name }} . {{ $item->segment->name }}</span>
												<br>
												<span class="text-muted">{{ $item->subscribers()->count() }} {{ strtolower(trans('messages.subscribers')) }}</span>
											@elseif (is_object($item->mailList))
												<span class="no-margin stat-num">{{ $item->mailList->name }}</span>
												<br>
												<span class="text-muted">{{ $item->subscribers()->count() }} {{ strtolower(trans('messages.subscribers')) }}</span>
											@endif
										</td>
										<td>
											@if (isset($item->delivery_at) && $item->delivery_at != "0000-00-00 00:00:00")
												<span class="no-margin stat-num"><i class="icon-alarm"></i> {{ Tool::formatDateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->delivery_at)) }}</span>
												<br>
												<span class="text-muted">{{ trans('messages.delivery_at') }}</span>
											@endif
										</td>
										<td class="text-right">
											<span class="text-muted2 list-status pull-left">
												<span class="label label-flat bg-{{ $item->status }}">{{ $item->status }}</span>
											</span>											
											@if (\Gate::allows('update', $item))
												<a href="{{ action('CampaignController@edit', $item->uid) }}" type="button" class="btn bg-grey btn-icon"> <i class="icon-pencil"></i> {{ trans('messages.edit') }}</a>
											@endif
											@if (\Gate::allows('overview', $item))
												<a href="{{ action('CampaignController@overview', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.overview') }}" type="button" class="btn bg-grey btn-icon"><i class="icon-stats-growth"></i> <i class="icon-search4"></i></a>
											@endif
											@if (\Gate::allows('delete', $item))
												<div class="btn-group">										
													<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret ml-0"></span></button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li><a delete-confirm="{{ trans('messages.delete_campaigns_confirm') }}" href="{{ action('CampaignController@delete', ["uids" => $item->uid]) }}"><i class="icon-trash"></i> {{ trans("messages.delete") }}</a></li>
													</ul>
												</div>
											@endif
										</td>
									</tr>
								@endforeach
							</table>
                            @include('elements/_per_page_select')
							{{ $campaigns->links() }}
						@elseif (!empty(request()->keyword))
							<div class="empty-list">
								<i class="icon-paperplane"></i>
								<span class="line-1">
									{{ trans('messages.no_search_result') }}
								</span>
							</div>
						@else					
							<div class="empty-list">
								<i class="icon-paperplane"></i>
								<span class="line-1">
									{{ trans('messages.campaign_empty_line_1') }}
								</span>
							</div>
						@endif