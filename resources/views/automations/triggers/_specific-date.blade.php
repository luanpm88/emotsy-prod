                            <div class="col-md-6">
								<hr>
								<div class="row">
									<div class="col-md-6">
										@include('helpers.form_control', [
											'type' => 'date',
											'class' => '_from_now',
											'name' => 'send_day',
											'label' => trans('messages.Send_on'),
											'value' => Acelle\Library\Tool::dayStringFromTimestamp($automation->trigger->send_at),
											'rules' => $automation->trigger->rules(),
											'help_class' => 'trigger'
										])
									</div>
									<div class="col-md-6">
										<div class="row pt-29">
											<div class="col-md-2">
												<label class="col-center">{{ trans('messages.at') }}</label>
											</div>
											<div class="col-md-10">
												@include('helpers.form_control', [
													'type' => 'time',
													'name' => 'send_time',
													'label' => '',
													'value' => Acelle\Library\Tool::timeStringFromTimestamp($automation->trigger->send_at),
													'rules' => $automation->trigger->rules(),
													'help_class' => 'trigger'
												])
											</div>
										</div>
									</div>
								</div>
							</div>