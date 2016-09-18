                        @if ($customers->count() > 0)
							<table class="table table-box pml-table"
                                current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
                            >
								@foreach ($customers as $key => $item)
									<tr>
										<td width="1%">
											<img width="80" class="img-circle mr-10" src="{{ action('CustomerController@avatar', $item->uid) }}" alt="">
										</td>
										<td>
											<h5 class="no-margin text-bold">
												<a class="kq_search" href="{{ action('Admin\CustomerController@edit', $item->uid) }}">{{ $item->displayName() }}</a>
											</h5>
											<span class="text-muted kq_search">{{ $item->user->email }}</span>
											<br />
											{{ $item->customerGroup->name }}
											<br />
											<span class="text-muted2">{{ trans('messages.created_at') }}: {{ Tool::formatDateTime($item->created_at) }}</span>
										</td>
										<td class="stat-fix-size">
											
										</td>
										<td class="text-right">
											<span class="text-muted2 list-status pull-left">
												<span class="label label-flat bg-{{ $item->status }}">{{ $item->status }}</span>
											</span>
											@can('switch_customer', $item)
												<a href="{{ action('Admin\CustomerController@switch_customer', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.login_as_this_customer') }}" type="button" class="btn bg-teal-600 btn-icon"><i class="glyphicon glyphicon-random pr-5"></i></a>
											@endcan
											@can('update', $item)
												<a href="{{ action('Admin\CustomerController@edit', $item->uid) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" type="button" class="btn bg-grey-600 btn-icon"><i class="icon icon-pencil pr-0 mr-0"></i></a>
											@endcan
											@can('delete', $item)
												<div class="btn-group">										
													<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret ml-0"></span></button>
													<ul class="dropdown-menu dropdown-menu-right">													
														<li>														
															<a delete-confirm="{{ trans('messages.delete_users_confirm') }}" href="{{ action('Admin\CustomerController@delete', ['uids' => $item->uid]) }}">
																<i class="icon-trash"></i> {{ trans('messages.delete') }}
															</a>
														</li>
													</ul>
												</div>
											@endcan
										</td>
									</tr>
								@endforeach
							</table>
                            @include('elements/_per_page_select')
							{{ $customers->links() }}
						@elseif (!empty(request()->filters))
							<div class="empty-list">
								<i class="icon-users"></i>
								<span class="line-1">
									{{ trans('messages.no_search_result') }}
								</span>
							</div>
						@else					
							<div class="empty-list">
								<i class="icon-users"></i>
								<span class="line-1">
									{{ trans('messages.customer_empty_line_1') }}
								</span>
							</div>
						@endif