                                <!-- Sales stats -->
								<div class="timeline-row condition-line" rel="{{ $auto_event->uid }}">
									<div class="timeline-icon timeline-icon-i">
                                        <i class="icon-paperplane text-teal"></i>
									</div>
									<div class="panel panel-flat timeline-content">
										<div class="panel-heading">
											<h6 class="panel-title text-semibold auto-event-form-line">
												Wait
                                                @include('helpers.form_control', [
                                                    'type' => 'text',
                                                    'name' => 'monthly_recurring_months[]',
                                                    'label' => '',
                                                    'value' => '1',
                                                    'rules' => []
                                                ])
                                                @include('helpers.form_control', [
                                                    'type' => 'select',
                                                    'name' => 'monthly_recurring_months[]',
                                                    'multiple' => '',
                                                    'label' => '',
                                                    'value' => 'day',
                                                    'options' => Acelle\Model\AutoEvent::timeUnitOptions(),
                                                    'rules' => []
                                                ])
                                                after previous email is
                                                @include('helpers.form_control', [
                                                    'type' => 'select',
                                                    'name' => 'monthly_recurring_months[]',
                                                    'multiple' => '',
                                                    'label' => '',
                                                    'value' => '1',
                                                    'options' => Acelle\Model\AutoEvent::emailEventOptions(),
                                                    'rules' => []
                                                ])
											</h6>
                                            <div class="heading-elements">
                                                <button class="btn btn-default"><i class="icon-checkmark4 mr-5"></i> {{ trans('messages.save') }}</button>
                                                <button
													data-confirm="{{ trans('messages.delete_auto_event_confirm') }}"
													data-url="{{ action('AutoEventController@delete', $auto_event->uid) }}"
													data-id="{{ $auto_event->uid }}"
													class="btn btn-default auto-event-delete">
														<i class="icon-trash"></i>
												</button>
                                            </div>
										</div>
                                    </div>
                                        
                                    <div class="event-campaigns-box">
										<div class="event-campaigns-container" data-url="{{ action('AutoEventController@campaigns', $auto_event->uid) }}">
											
										</div>
											
										<div class="text-center">
											<button class="btn btn-xs bg-teal event-campaign-add" data-url="{{ action('AutoEventController@addCampaign', $auto_event->uid) }}">
												<i class="icon-plus2"></i> {{ trans('messages.add_email') }}
											</button>
										</div>
									</div>
                                    
                                    <br /><br />
								</div>
								<!-- /sales stats -->