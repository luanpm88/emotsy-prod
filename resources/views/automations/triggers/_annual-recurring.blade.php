                            <div class="col-md-12">
                                <hr>
								<div class="row radio-box">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day" class="styled radio-button">
												{{ trans('messages.On_specific_date') }}
											</label>
										</div>
										<div class="pl-35 radio-more">
											<div class="row">
												<div class="col-md-3">
													<div class="">
														@include('helpers.form_control', [
															'type' => 'date',
															'class' => '_from_now',
															'name' => 'annual_day',
															'label' => '',
															'value' => '',
															'rules' => [],
															'help_class' => 'trigger'
														])
													</div>
												</div>
												<div class="col-md-3">
													<div class="row">
														<div class="col-md-2">
															<label class="col-center">{{ trans('messages.at') }}</label>
														</div>
														<div class="col-md-10">
															@include('helpers.form_control', [
																'type' => 'time',
																'name' => 'annual_time',
																'label' => '',
																'value' => Acelle\Library\Tool::timeStringFromTimestamp($first_event->send_at),
																'rules' => $first_event->rules(),
																'help_class' => 'trigger'
															])
														</div>
													</div>
												</div>
												<div class="col-md-2">
													<div class="row">
														<div class="col-md-12">
															<label class="col-center">{{ trans('messages.every_year') }}</label>
														</div>														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row radio-box">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day-of-week" class="styled radio-button">
												{{ trans('messages.Day_of_the_week') }}
											</label>
										</div>
										<div class="pl-35 radio-more">
											<div class="row ">
												<div class="col-md-2">
													@include('helpers.form_control', [
														'type' => 'checkboxes',
														'name' => 'type',
														'class' => '',
														'label' => trans('messages.choose_a_day'),
														'value' => '',
														'options' => Acelle\Library\Tool::dayOfWeekSelectOptions(),
														'rules' => []
													])
												</div>
												<div class="col-md-2">
													@include('helpers.form_control', [
														'type' => 'time',
														'name' => 'specific_time_2',
														'label' => ucfirst(trans('messages.at')),
														'value' => Acelle\Library\Tool::timeStringFromTimestamp($first_event->send_at),
														'rules' => $first_event->rules(),
														'help_class' => 'trigger'
													])
												</div>
												<div class="col-md-2">
													@include('helpers.form_control', [
														'type' => 'checkboxes',
														'name' => 'type',
														'multiple' => '',
														'label' => ucfirst(trans('messages.weeks')),
														'value' => '',
														'options' => Acelle\Library\Tool::weekSelectOptions(),
														'rules' => []
													])
												</div>
												<div class="col-md-2">
													@include('helpers.form_control', [
														'type' => 'checkboxes',
														'name' => 'type',
														'multiple' => '',
														'label' => ucfirst(trans('messages.months')),
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
								<div class="row radio-box">
									<div class="col-md-12">
										<div class="radio form-group">
											<label>
												<input type="radio" id="" name="specific_date_type" value="specific-day-of-week" class="styled radio-button">
												{{ trans('messages.Day_of_the_month') }}
											</label>
										</div>
										<div class="pl-35 radio-more">
											<div class="row ">
												<div class="col-md-2 day-of-month-box">
													@include('helpers.form_control', [
														'type' => 'checkboxes',
														'name' => 'type',
														'class' => '',
														'label' => trans('messages.choose_a_day'),
														'value' => '',
														'options' => Acelle\Library\Tool::dayOfMonthSelectOptions(),
														'rules' => []
													])
												</div>
												<div class="col-md-2">
													@include('helpers.form_control', [
														'type' => 'time',
														'name' => 'specific_time_3',
														'label' => ucfirst(trans('messages.at')),
														'value' => Acelle\Library\Tool::timeStringFromTimestamp($first_event->send_at),
														'rules' => $first_event->rules(),
														'help_class' => 'trigger'
													])
												</div>
												<div class="col-md-2">
													@include('helpers.form_control', [
														'type' => 'checkboxes',
														'name' => 'type',
														'multiple' => '',
														'label' => ucfirst(trans('messages.months')),
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