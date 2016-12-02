                                    @foreach($campaigns as $key => $campaign)
										<div class="panel panel-flat panel-auto-campaign timeline-content ml-20">
											<div class="panel-body">
												<table>
													<tr>
														<td width="1%">
															<i class="icon-envelop auto-campaign-icon"></i>
														</td>
														<td>
															<h6 class="text-semibold mt-0 mb-0">
																{{ $campaign->name }}
															</h6>
															<span class="text-muted2">{{ trans('messages.updated_at') }}: {{ Tool::formatDateTime($campaign->created_at) }}</span>
														</td>
														<td class="text-right">
															<button class="btn btn-default"><i class="icon-pencil mr-5"></i> {{ trans('messages.design_email') }}</button>
															<button class="btn btn-default auto-campaign-delete"
																data-confirm="{{ trans('messages.delete_auto_campaign_confirm', ['name' => $campaign->name]) }}"
																data-url="{{ action('AutoEventController@deleteCampaign', $campaign->uid) }}">
																	<i class="icon-trash"></i>
															</button>
														</td>
													</tr>
												</table>
											</div>
										</div>
									@endforeach