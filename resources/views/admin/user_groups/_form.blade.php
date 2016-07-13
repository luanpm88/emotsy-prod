                    <div class="sub_section">
						<div class="row">
							<div class="col-md-12">									

									@include('helpers.form_control', ['type' => 'text', 'name' => 'name', 'value' => $group->name, 'help_class' => 'user_group', 'rules' => Mailixa\Model\UserGroup::$rules])

							</div>
						</div>
						<div class="row">
							<div class="col-md-6">									
								<div class="form-group checkbox-right-switch">
									@include('helpers.form_control', ['type' => 'checkbox',
																		'name' => 'frontend_access',
																		'value' => $group->frontend_access,
																		'help_class' => 'user_group',
																		'options' => [false, true],
																		'rules' => Mailixa\Model\UserGroup::rules()
																	])
								</div>
							</div>
							<div class="col-md-6">									
								<div class="form-group checkbox-right-switch">
									@include('helpers.form_control', ['type' => 'checkbox',
																		'name' => 'backend_access',
																		'value' => $group->backend_access,
																		'help_class' => 'user_group',
																		'options' => [false, true],
																		'rules' => Mailixa\Model\UserGroup::rules()
																	])
								</div>
							</div>
						</div>
					</div>
					
					<div class="options-container">
						<h2><i class="icon-gear"></i> {{ trans('messages.user_group_options') }}</h2>
							
						<div class="tabbable">
							<ul class="nav nav-tabs nav-tabs-top">
								<li class="active text-semibold frontend-box"><a href="#top-tab1" data-toggle="tab">
									<i class="icon-user"></i> {{ trans('messages.frontend') }}</a></li>
								<li class="text-semibold backend-box"><a href="#top-tab2" data-toggle="tab">
									<i class="icon-user-tie"></i> {{ trans('messages.backend') }}</a></li>
							</ul>
	
							<div class="tab-content">
								<div class="tab-pane active frontend-box" id="top-tab1">
									<h3 class="text-teal-800">{{ trans('messages.list_or_subscriber_or_segment') }}</h3>
									<div class="row">
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][list_max]',
																			'value' => $options['frontend']['list_max'],
																			'label' => trans('messages.max_lists'),
																			'help_class' => 'user_group',
																			'options' => ['true', 'false'],
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['list_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
										</div>
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][subscriber_max]',
																			'value' => $options['frontend']['subscriber_max'],
																			'label' => trans('messages.max_subscribers'),
																			'help_class' => 'user_group',
																			'options' => ['true', 'false'],
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['subscriber_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
										</div>
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][subscriber_per_list_max]',
																			'value' => $options['frontend']['subscriber_per_list_max'],
																			'label' => trans('messages.max_subscribers_per_list'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['subscriber_per_list_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
										</div>
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][segment_per_list_max]',
																			'value' => $options['frontend']['segment_per_list_max'],
																			'label' => trans('messages.segment_per_list_max'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['segment_per_list_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
										</div>
									</div>
										
									<h3 class="text-teal-800">{{ trans('messages.campaign') }}</h3>
									<div class="row">
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][campaign_max]',
																			'value' => $options['frontend']['campaign_max'],
																			'label' => trans('messages.max_campaigns'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['campaign_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
																		
										</div>
										<div class="col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][campaign_subscribers_at_once]',
																			'value' => $options['frontend']['campaign_subscribers_at_once'],
																			'label' => trans('messages.subscribers_at_once'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
										</div>
										<div class="col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][campaign_pause]',
																			'value' => $options['frontend']['campaign_pause'],
																			'label' => trans('messages.campaign_pause_time'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
										</div>
										<div class="col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][campaign_emails_per_minute]',
																			'value' => $options['frontend']['campaign_emails_per_minute'],
																			'label' => trans('messages.emails_per_minute'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
										</div>
									</div>
									<div class="row">									
										<div class="col-md-3">
											@include('helpers.form_control', ['type' => 'text',
												'class' => 'numeric',
												'name' => 'options[frontend][campaign_change_server_time]',
												'value' => $options['frontend']['campaign_change_server_time'],
												'label' => trans('messages.change_server_time'),
												'help_class' => 'user_group',
												'rules' => Mailixa\Model\UserGroup::rules()
											])
										</div>
										
										<div class="col-md-3">
											@include('helpers.form_control', ['type' => 'text',
												'class' => 'numeric',
												'name' => 'options[frontend][campaign_send_at_once]',
												'value' => $options['frontend']['campaign_send_at_once'],
												'label' => trans('messages.send_at_once'),
												'help_class' => 'user_group',
												'rules' => Mailixa\Model\UserGroup::rules()
											])
										</div>
									</div>
										
									<h3 class="text-teal-800">{{ trans('messages.sending_quota') }}</h3>
									<div class="row">
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][sending_quota]',
																			'value' => $options['frontend']['sending_quota'],
																			'label' => trans('messages.sending_quota'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['sending_quota']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
										</div>
										<div class="boxing col-md-3">
											@include('helpers.form_control', ['type' => 'text',
																			'class' => 'numeric',
																			'name' => 'options[frontend][sending_quota_time]',
																			'value' => $options['frontend']['sending_quota_time'],
																			'label' => trans('messages.quota_time'),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
											<div class="checkbox inline unlimited-check text-semibold">
												<label>
													<input{{ $options['frontend']['sending_quota_time']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
													{{ trans('messages.unlimited') }}
												</label>
											</div>
										</div>
										<div class="col-md-3">
											@include('helpers.form_control', ['type' => 'select',
												'name' => 'options[frontend][sending_quota_time_unit]',
												'value' => $options['frontend']['sending_quota_time_unit'],
												'label' => trans('messages.quota_time_unit'),
												'options' => Mailixa\Model\UserGroup::timeUnitOptions(),
												'include_blank' => trans('messages.choose'),
												'help_class' => 'user_group',
												'rules' => Mailixa\Model\UserGroup::rules()
											])
										</div>
									</div>
										
									<h3 class="text-teal-800">{{ trans('messages.sending_servers') }}</h3>
										
									@include('helpers.form_control', ['type' => 'checkboxes',
																			'name' => 'sending_server_ids[]',
																			'value' => $sending_server_ids,
																			'label' => trans('messages.select_sending_servers_for_group'),
																			'options' => Mailixa\Model\SendingServer::getSelectOptions(),
																			'help_class' => 'user_group',
																			'rules' => Mailixa\Model\UserGroup::rules()
																		])
								</div>
								
								<div class="tab-pane backend-box" id="top-tab2">
									
									@foreach (Mailixa\Model\UserGroup::backendPermissions() as $key => $items)
										<h3 class="text-teal-800">{{ trans('messages.' . $key) }}</h3>
										<div class="row">
											@foreach ($items as $act => $ops)
												<div class="col-md-3">
													@if (count($ops["options"]) > 2)
														@include('helpers.form_control', ['type' => 'select',
															'class' => 'numeric',
															'name' => 'options[backend][' . $key . "_" . $act .']',
															'value' => $options['backend'][$key . "_" . $act],
															'label' => trans('messages.' . $act),
															'options' => $ops["options"],
															'help_class' => 'user_group',
															'rules' => Mailixa\Model\UserGroup::rules()
														])
													@else
														<div class="checkbox-box-group">
															@include('helpers.form_control', ['type' => 'checkbox',
																'class' => 'numeric',
																'name' => 'options[backend][' . $key . "_" . $act .']',
																'value' => $options['backend'][$key . "_" . $act],
																'label' => trans('messages.' . $act),
																'options' => ['no','yes'],
																'help_class' => 'user_group',
																'rules' => Mailixa\Model\UserGroup::rules()
															])
														</div>
													@endif
												</div>
											@endforeach
										</div>
									@endforeach
									
								</div>
	
							</div>
						</div>
					</div>
					
					
					