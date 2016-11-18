                    <div class="row">
						<div class="col-md-6">
							@include('helpers.form_control', [
								'type' => 'text',
								'name' => 'name',
                                'value' => $automation->name,
								'label' => trans('messages.Name_your_automation'),								
								'rules' => $automation->rules()["init"]
							])
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 list_select_box" target-box="segments-select-box" segments-url="{{ action('SegmentController@selectBox') }}">
							@include('helpers.form_control', [								
								'type' => 'select',
								'name' => 'mail_list_uid',
                                'value' => (is_object($automation->mailList) ? $automation->mailList->uid : ""),
								'label' => trans('messages.which_list_send'),								
								'options' => Acelle\Model\MailList::getSelectOptions(Auth::user()),
                                'include_blank' => trans('messages.choose'),
								'rules' => $automation->rules()["init"]
							])
						</div>
						<div class="col-md-6 segments-select-box">
							@if (is_object($automation->mailList) && $automation->mailList->segments()->count())
								@include('helpers.form_control', [
									'type' => 'select',
									'value' => '',
									'name' => 'segment_uid',
									'label' => trans('messages.which_segment_send'),
                                    'include_blank' => trans('messages.choose'),
									'options' => $automation->mailList->getSegmentSelectOptions()
								])
							@endif
						</div>
					</div>