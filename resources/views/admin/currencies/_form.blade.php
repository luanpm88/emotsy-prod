					<div class="row">						
						<div class="col-md-4">
							<div class="">
								
								@include('helpers.form_control', ['type' => 'text', 'name' => 'name', 'value' => $currency->name, 'help_class' => 'currency', 'rules' => $currency->rules()])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="">

								@include('helpers.form_control', ['type' => 'text', 'name' => 'code', 'value' => $currency->code, 'help_class' => 'currency', 'rules' => $currency->rules()])
								
							</div>
						</div>						
					</div>
					
					<hr />
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>