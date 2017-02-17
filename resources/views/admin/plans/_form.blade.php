<div class="row">						
	<div class="col-md-4">
		<div class="">
			@include('helpers.form_control', ['type' => 'text', 'name' => 'name', 'value' => $plan->name, 'help_class' => 'plan', 'rules' => $plan->rules()])
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
				'options' => $plan->colors("color"),
				'rules' => '',
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
				'class' => 'numeric',
				'type' => 'text',
				'name' => 'frequency_amount',
				'value' => $plan->frequency_amount,
				'help_class' => 'plan',
				'rules' => $plan->rules()
			])
		</div>
	</div>
	<div class="col-md-4">
		<div class="">
			@include('helpers.form_control', ['type' => 'select',
				'name' => 'frequency_unit',
				'value' => $plan->frequency_unit,
				'options' => $plan->timeUnitOptions(),
				'include_blank' => trans('messages.choose'),
				'help_class' => 'plan',
				'rules' => $plan->rules()
			])
		</div>
	</div>						
	
</div>
	

<div class="">
	<h2><i class="icon-gear"></i> {{ trans('messages.customer_group_options') }}</h2>
		
	<div class="tabbable">
		<ul class="nav nav-tabs nav-tabs-top">
			<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
				<i class="icon-user"></i> {{ trans('messages.frontend') }}</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="top-tab1">
				<h3 class="text-teal-800">{{ trans('messages.list_or_subscriber_or_segment_or_campaign') }}</h3>
				<div class="row">
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[list_max]',
							'value' => $options['list_max'],
							'label' => trans('messages.max_lists'),
							'help_class' => 'plan',
							'options' => ['true', 'false'],
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['list_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
					</div>
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[subscriber_max]',
							'value' => $options['subscriber_max'],
							'label' => trans('messages.max_subscribers'),
							'help_class' => 'plan',
							'options' => ['true', 'false'],
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['subscriber_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
					</div>
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[subscriber_per_list_max]',
							'value' => $options['subscriber_per_list_max'],
							'label' => trans('messages.max_subscribers_per_list'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['subscriber_per_list_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
					</div>
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[segment_per_list_max]',
							'value' => $options['segment_per_list_max'],
							'label' => trans('messages.segment_per_list_max'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['segment_per_list_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
					</div>										
				</div>
				<div class="row">
					<div class="boxing col-md-3">
						@include('helpers.form_control', ['type' => 'text',
							'class' => 'numeric',
							'name' => 'options[campaign_max]',
							'value' => $options['campaign_max'],
							'label' => trans('messages.max_campaigns'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['campaign_max']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
													
					</div>
					<div class="boxing col-md-3">
						<label class="text-semibold">{{ trans('messages.unsubscribe_url_required') }} <span class="text-danger">*</span></label>
						<br />
						<span class="notoping">
							@include('helpers.form_control', ['type' => 'checkbox',
								'class' => '',
								'name' => 'options[unsubscribe_url_required]',
								'value' => $options['unsubscribe_url_required'],
								'label' => '',
								'options' => ['no','yes'],
								'help_class' => 'plan',
								'rules' => $plan->rules()
							])
						</span>
					</div>
					<div class="boxing col-md-3">
						<label class="text-semibold">{{ trans('messages.access_when_offline') }} <span class="text-danger">*</span></label>
						<br />
						<span class="notoping">
							@include('helpers.form_control', ['type' => 'checkbox',
								'class' => '',
								'name' => 'options[access_when_offline]',
								'value' => $options['access_when_offline'],
								'label' => '',
								'options' => ['no','yes'],
								'help_class' => 'plan',
								'rules' => $plan->rules()
							])
						</span>
					</div>
				</div>
					
				<h3 class="text-teal-800">{{ trans('messages.file_upload') }}</h3>
				<div class="row">
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[max_size_upload_total]',
							'value' => $options['max_size_upload_total'],
							'label' => trans('messages.max_size_upload_total'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
					</div>
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[max_file_size_upload]',
							'value' => $options['max_file_size_upload'],
							'label' => trans('messages.max_file_size_upload'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
					</div>		
				</div>
				
					
				<h3 class="text-teal-800">{{ trans('messages.sending_quota') }}</h3>
				<div class="row">
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[sending_quota]',
							'value' => $options['sending_quota'],
							'label' => trans('messages.sending_quota'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['sending_quota']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
					</div>
					<div class="boxing col-md-3">
						@include('helpers.form_control', [
							'type' => 'text',
							'class' => 'numeric',
							'name' => 'options[sending_quota_time]',
							'value' => $options['sending_quota_time'],
							'label' => trans('messages.quota_time'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
						<div class="checkbox inline unlimited-check text-semibold">
							<label>
								<input{{ $options['sending_quota_time']  == -1 ? " checked=checked" : "" }} type="checkbox" class="styled">
								{{ trans('messages.unlimited') }}
							</label>
						</div>
					</div>
					<div class="col-md-3">
						@include('helpers.form_control', ['type' => 'select',
							'name' => 'options[sending_quota_time_unit]',
							'value' => $options['sending_quota_time_unit'],
							'label' => trans('messages.quota_time_unit'),
							'options' => Acelle\Model\CustomerGroup::timeUnitOptions(),
							'include_blank' => trans('messages.choose'),
							'help_class' => 'plan',
							'rules' => $plan->rules()
						])
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="mt-0 mb-5 text-semibold">{{ trans('messages.max_number_of_processes') }}</label>
							@include('helpers.form_control', ['type' => 'select',
								'name' => 'options[max_process]',
								'value' => $options['max_process'],
								'label' => '',
								'options' => $plan->multiProcessSelectOptions(),
								'help_class' => 'plan',
								'rules' => $plan->rules()
							])
						</div>
					</div>
				</div>
					
				<h3 class="text-teal-800">{{ trans('messages.sending_servers') }}</h3>
				<div class="row">
					<div class="col-md-3">
						{{ trans('messages.all_sending_servers') }}&nbsp;&nbsp;&nbsp;
						<span class="notoping">												
							@include('helpers.form_control', ['type' => 'checkbox',
								'class' => '',
								'name' => 'options[all_sending_servers]',
								'value' => $options['all_sending_servers'],
								'label' => '',
								'options' => ['no','yes'],
								'help_class' => 'plan',
								'rules' => $plan->rules()
							])
						</span>
						
					</div>
				</div>
				<br />
				<div class="row sending-servers">
					@foreach (Acelle\Model\SendingServer::getAll()->orderBy("name")->get() as $server)
																
						<div class="col-md-6">
							<h5 class="mt-0 mb-5 text-semibold text-teal-600">{{ $server->name }}</h5>
							<div class="row">											
								<div class="col-md-2">
									<div class="form-group">
										<label class="mt-0 mb-5 text-semibold">{{ trans('messages.choose') }}</label>
										@include('helpers.form_control', [
											'type' => 'checkbox',
											'name' => 'sending_servers[' . $server->uid . '][check]',
											'value' => $plan->plansSendingServers->contains('sending_server_id', $server->id),
											'label' => '',
											'options' => [false, true],
											'help_class' => 'plan',
											'rules' => $plan->rules()
										])
									</div>
									<br><br>
								</div>
								<div class="col-md-9">
									@include('helpers.form_control', [
										'type' => 'text',
										'class' => 'numeric',
										'name' => 'sending_servers[' . $server->uid . '][fitness]',
										'label' => trans('messages.fitness'),
										'value' => (is_object($plan->plansSendingServers()->where('sending_server_id', $server->id)->first()) ? $plan->plansSendingServers()->where('sending_server_id', $server->id)->first()->fitness : "100"),
										'help_class' => 'plan',
										'rules' => $plan->rules()
									])
								</div>
							</div>
						</div>
							
					@endforeach
				</div>
					
			</div>
				
		</div>
	</div>
</div>
	
	
<hr />
<div class="text-left">
	<button type='submit' class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>
	
<script>
	function changeSelectColor() {
		$('.select2 .select2-selection__rendered, .select2-results__option').each(function() {							
			var text = $(this).html();
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

		// all sending servers checking
		$(document).on("change", "input[name='options[all_sending_servers]']", function(e) {
			if($("input[name='options[all_sending_servers]']:checked").length) {									
				$(".sending-servers").find("input[type=checkbox]").each(function() {
					if($(this).is(":checked")) {
						$(this).parents(".form-group").find(".switchery").eq(1).click();
					}
				});
				$(".sending-servers").hide();
			} else {
				$(".sending-servers").show();
			}
		});
		
		$("input[name='options[all_sending_servers]']").trigger("change");
	});
</script>