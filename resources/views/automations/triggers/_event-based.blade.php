                            
                            <div class="col-md-6">
                                <hr>
                                <h5 class="text-semibold">{{ trans('messages.Trigger_the_automation_for_the_following_recur') }}</h5>
								<div class="row">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day" class="styled">
												{{ trans('messages.List_creation_date') }}
											</label>
										</div>
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day" class="styled">
												{{ trans('messages.Subscriber_added_date') }}
											</label>
										</div>
                                        <div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day" class="styled">
												{{ trans('messages.Subscriber_field_tag', [
                                                    'tag' => 'BIRTHDAY',
                                                ]) }}
											</label>
										</div>
									</div>									
								</div>
							</div>