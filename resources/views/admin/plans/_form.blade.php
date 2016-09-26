					<div class="row">						
						<div class="col-md-4">
							<div class="">
								
								@include('helpers.form_control', ['type' => 'text', 'name' => 'name', 'value' => $plan->name, 'help_class' => 'plan', 'rules' => $plan->rules()])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="">

								@include('helpers.form_control', ['type' => 'select',
																'name' => 'customer_group_id',
																'label' => trans('messages.customer_group'),
																'value' => $plan->customer_group_id,
																'options' => Acelle\Model\CustomerGroup::getSelectOptions(),
																'include_blank' => trans('messages.choose'),
																'help_class' => 'plan',
																'rules' => $plan->rules()
															])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="">

								@include('helpers.form_control', ['type' => 'select',
																'name' => 'frequency',
																'label' => trans('messages.frequency'),
																'value' => $plan->frequency,
																'options' => Acelle\Model\Plan::frequencySelectOptions(),
																'include_blank' => trans('messages.choose'),
																'help_class' => 'plan',
																'rules' => $plan->rules()
															])
								
							</div>
						</div>
					</div>
					<div class="row">						
						<div class="col-md-4">
							<div class="">
								
								@include('helpers.form_control', ['class' => 'numeric', 'type' => 'text', 'name' => 'price', 'value' => $plan->price, 'help_class' => 'plan', 'rules' => $plan->rules()])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="">
								
								@include('helpers.form_control', [
									'type' => 'select',
									'class' => '',
									'name' => 'color',
									'value' => $plan->color,
									'help_class' => 'admin',
									'options' => Acelle\Model\Admin::colors("color"),
									'rules' => '',
								])
								
							</div>
						</div>
					</div>
					<hr />
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>
						
					<script>
				function changeSelectColor() {
					$('.select2 .select2-selection__rendered, .select2-results__option').each(function() {							
						var text = $(this).html();
						if (text == '{{ trans('messages.default') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-teal-600'></i>");
							}
						}
						if (text == '{{ trans('messages.blue') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-blue'></i>");
							}
						}
						if (text == '{{ trans('messages.green') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-green'></i>");
							}
						}
						if (text == '{{ trans('messages.brown') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-brown'></i>");
							}
						}
						if (text == '{{ trans('messages.pink') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-pink'></i>");
							}
						}
						if (text == '{{ trans('messages.grey') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-grey'></i>");
							}
						}
						if (text == '{{ trans('messages.white') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-white'></i>");
							}
						}
					});
				}
				
				$(document).ready(function() {
					setInterval("changeSelectColor()", 100);
				});
			</script>