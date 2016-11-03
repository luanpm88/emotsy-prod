                        @if ($items->count() > 0)
							<table class="table table-box pml-table"
                                current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
                            >
								@foreach ($items as $key => $item)
									<tr>
										<td width="1%">
											<i class="glyphicon glyphicon-flag table-row-icon"></i>
										</td>
										<td>
											<h5 class="no-margin text-bold">
												<a class="kq_search" href="{{ action('Admin\LanguageController@edit', $item->uid) }}">{{ $item->name }}</a>
											</h5>
											<span class="text-muted">{{ trans('messages.created_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
										</td>
										<td>
											<span class="no-margin stat-num kq_search">{{ $item->code }}</span>
											<br />
											<span class="text-muted">{{ trans('messages.code') }}</span>
										</td>
										<td>
											<span class="no-margin stat-num kq_search">{{ $item->region_code }}</span>
											<br />
											<span class="text-muted">{{ trans('messages.region') }}</span>
										</td>
										<td class="text-right">
											<span class="text-muted2 list-status pull-left">
												<span class="label label-flat bg-{{ $item->status }}">{{ $item->status }}</span>
											</span>											
											@can("translate", $item)
												<a href="{{ action('Admin\LanguageController@translate', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.translate') }}" type="button" class="btn bg-teal btn-icon"><i class="icon-pencil"></i> {{ trans('messages.translate') }}</a>
											@endcan
											@if(Auth::user()->can("delete", $item) || Auth::user()->can("update", $item))
												<div class="btn-group">										
													<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret ml-0"></span></button>
													<ul class="dropdown-menu dropdown-menu-right">												
														@can("update", $item)
															<li>
																<a href="{{ action('Admin\LanguageController@edit', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}"><i class="icon-pencil"></i> {{ trans('messages.edit') }}</a>
															</li>
														@endcan
														@can("update", $item)
															<li>
																<a delete-confirm="{{ trans('messages.delete_languages_confirm') }}" href="{{ action('Admin\LanguageController@delete', ["uids" => $item->uid]) }}">
																	<i class="icon-trash"></i> {{ trans('messages.delete') }}
																</a>
															</li>
														@endcan
														</li>
													</ul>
												</div>
											@endcan
										</td>
									</tr>
								@endforeach
							</table>
                            @include('elements/_per_page_select')
							{{ $items->links() }}
						@elseif (!empty(request()->keyword))
							<div class="empty-list">
								<i class="glyphicon glyphicon-flag"></i>
								<span class="line-1">
									{{ trans('messages.no_search_result') }}
								</span>
							</div>
						@else					
							<div class="empty-list">
								<i class="glyphicon glyphicon-flag"></i>
								<span class="line-1">
									{{ trans('messages.language_empty_line_1') }}
								</span>
							</div>
						@endif