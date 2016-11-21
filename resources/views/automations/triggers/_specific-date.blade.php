                            <div class="col-md-6">
								<hr>
								<div class="row">
									<div class="col-md-6">
										@include('helpers.form_control', [
											'type' => 'date',
											'class' => '_from_now',
											'name' => 'send_on',
											'label' => trans('messages.Send_on'),
											'value' => '',
											'rules' => [],
											'help_class' => 'trigger'
										])
									</div>
									<div class="col-md-6 segments-select-box">
										@include('helpers.form_control', [
											'type' => 'time',
											'name' => 'send_at',
											'label' => trans('messages.at'),
											'value' => '',
											'rules' => [],
											'help_class' => 'trigger'
										])
									</div>
								</div>
							</div>