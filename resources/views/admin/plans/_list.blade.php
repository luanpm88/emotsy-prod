                        @if ($plans->count() > 0)
							<div class="row boxes-list boxes-plans table-box pml-table"
                                current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
                            >
								@foreach ($plans as $key => $item)
									<div class="col-md-3">
									
										<table class="table table-box pml-table hide"
											current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
										></table>
										
										<input type="hidden" class="node styled"
											custom-order="{{ $item->custom_order }}"
											name="ids[]"
											value="{{ $item->uid }}"
										/>
									
										<div class="panel panel-plan box-{{ $item->status }}">
											<div class="panel-heading" style="background-color: {{ $item->color }}; color: white">
												<div class="pull-right">
													@can('enable', $item)
														<a link-confirm="{{ trans('messages.enable_plans_confirm') }}" href="{{ action('Admin\PlanController@enable', ['uids' => $item->uid]) }}" title="{{ trans('messages.enable') }}" type="button" class="btn btn-white btn-icon"><i class="icon-checkbox-unchecked2"></i></a>
													@endcan
													@can('disable', $item)
														<a link-confirm="{{ trans('messages.disable_plans_confirm') }}" href="{{ action('Admin\PlanController@disable', ['uids' => $item->uid]) }}" title="{{ trans('messages.disable') }}" type="button" class="btn btn-white btn-icon"><i class="icon-checkbox-checked2"></i></a>
													@endcan
												</div>
												<h4 class="pull-left panel-title text-center">													
													{{ $item->name }}
												</h4>
											</div>
											<div class="plan-price-box">
												<span class="price">{{ $item->price }}</span> / {{ $item->frequency_amount }} {{ \Acelle\Library\Tool::getPluralPrase($item->frequency_unit, $item->frequency_amount)  }}
											</div>
											<div class="panel-body pt-0">
												<ul class="mt-0 mb-0 top-border-none plans-intro">
													<li>
														{!! trans('messages.sending_quota_intro', ["value" => $item->displayQuota()]) !!}
													</li>
													<li>
														{!! trans('messages.max_lists_intro', ["value" => $item->displayMaxList()]) !!}
													</li>
													<li>
														{!! trans('messages.max_subscribers_intro', ["value" => $item->displayMaxSubscriber()]) !!}
													</li>
													<li>
														{!! trans('messages.max_campaigns_intro', ["value" => $item->displayMaxCampaign()]) !!}
													</li>
													<li>
														{!! trans('messages.max_size_upload_total_intro', ["value" => $item->displayMaxSizeUploadTotal()]) !!}
													</li>
													<li>
														{!! trans('messages.max_file_size_upload_intro', ["value" => $item->displayFileSizeUpload()]) !!}
													</li>
												</ul>
											</div>
											<div class="panel-footer pt-10 pb-10 text-center">												
												@can('update', $item)
													<a href="{{ action('Admin\PlanController@edit', $item->uid) }}" title="{{ trans('messages.edit') }}" type="button" class="btn bg-grey-800 btn-icon"><i class="icon icon-pencil pr-0 mr-0"></i></a>
												@endcan
												@can('delete', $item)
													<a delete-confirm="{{ trans('messages.delete_currencies_confirm') }}" href="{{ action('Admin\PlanController@delete', ['uids' => $item->uid]) }}" title="{{ trans('messages.delete') }}" type="button" class="btn btn-danger btn-icon"><i class="icon icon-cross2"></i></a>
												@endcan												
											</div>
										</div>
									</div>
								@endforeach
							</div>
                            @include('elements/_per_page_select')
							{{ $plans->links() }}
						@elseif (!empty(request()->keyword))
							<div class="empty-list">
								<i class="icon-clipboard2"></i>
								<span class="line-1">
									{{ trans('messages.no_search_result') }}
								</span>
							</div>
						@else					
							<div class="empty-list">
								<i class="icon-clipboard2"></i>
								<span class="line-1">
									{{ trans('messages.plan_empty_line_1') }}
								</span>
							</div>
						@endif