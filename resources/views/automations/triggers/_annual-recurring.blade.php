                            <div class="col-md-6">
                                <hr>
								<div class="row">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day" class="styled">
												{{ trans('messages.On_specific_date') }}
											</label>
										</div>
										<div class="pl-35">
											<div class="row">
												<div class="col-md-6">
													<div class="">
														@include('helpers.form_control', [
															'type' => 'date',
															'class' => '_from_now',
															'name' => 'specific_day',
															'label' => '',
															'value' => '',
															'rules' => [],
															'help_class' => 'trigger'
														])
													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-2">
															<label class="col-center">{{ trans('messages.at') }}</label>
														</div>
														<div class="col-md-10">
															@include('helpers.form_control', [
																'type' => 'time',
																'name' => 'specific_time',
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
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day-of-week" class="styled">
												{{ trans('messages.Day_of_the_week') }}
											</label>
										</div>
										<div class="pl-35">
											<div class="row ">
												<div class="col-md-6">
													@include('helpers.form_control', [
														'type' => 'select',
														'name' => 'type',
														'class' => '',
														'label' => '',
														'value' => '',
														'options' => Acelle\Library\Tool::dayOfWeekSelectOptions(),
														'rules' => []
													])
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-2">
															<label class="col-center">{{ trans('messages.at') }}</label>
														</div>
														<div class="col-md-10">
															@include('helpers.form_control', [
																'type' => 'time',
																'name' => 'specific_time_2',
																'label' => '',
																'value' => Acelle\Library\Tool::timeStringFromTimestamp($automation->trigger->send_at),
																'rules' => $automation->trigger->rules(),
																'help_class' => 'trigger'
															])
														</div>
													</div>
												</div>											
											</div>
											<div class="row">
												<div class="col-md-6">
													@include('helpers.form_control', [
														'type' => 'select',
														'name' => 'type',
														'multiple' => '',
														'label' => '',
														'value' => '',
														'include_blank' => trans('messages.Every_month'),
														'options' => Acelle\Library\Tool::monthSelectOptions(),
														'rules' => []
													])
												</div>
												<div class="col-md-6">
													@include('helpers.form_control', [
														'type' => 'select',
														'name' => 'type',
														'multiple' => '',
														'label' => '',
														'value' => '',
														'options' => [
															['value' => 'specific-date', 'text' => trans('messages.Only_1st_week_of_month')],
															['value' => 'annual-recurring', 'text' => trans('messages.Every_week')],
														],
														'rules' => []
													])
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day-of-week" class="styled">
												{{ trans('messages.Day_of_the_month') }}
											</label>
										</div>
										<div class="pl-35">
											<div class="row">
												<div class="col-md-6">
													@include('helpers.form_control', [
														'type' => 'select',
														'name' => 'type',
														'class' => '',
														'label' => '',
														'value' => '',
														'options' => Acelle\Library\Tool::dayOfWeekSelectOptions(),
														'rules' => []
													])
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-2">
															<label class="col-center">{{ trans('messages.at') }}</label>
														</div>
														<div class="col-md-10">
															@include('helpers.form_control', [
																'type' => 'time',
																'name' => 'specific_time_3',
																'label' => '',
																'value' => Acelle\Library\Tool::timeStringFromTimestamp($automation->trigger->send_at),
																'rules' => $automation->trigger->rules(),
																'help_class' => 'trigger'
															])
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													@include('helpers.form_control', [
														'type' => 'select',
														'name' => 'type',
														'multiple' => '',
														'label' => '',
														'value' => '',
														'include_blank' => trans('messages.Every_month'),
														'options' => Acelle\Library\Tool::monthSelectOptions(),
														'rules' => []
													])
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>