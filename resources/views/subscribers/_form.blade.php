                    <div class="sub_section">
						<h2 class="text-semibold text-teal-800">{{ trans('messages.create_subscriber') }}</h2>
						<div class="row">
							<div class="col-md-6">									
								@foreach ($list->getFields as $field)
									@if ($field->visible)
										@if ($field->type == "text")
											@include('helpers.form_control', ['type' => $field->type, 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
										@else
										@endif
									@endif
								@endforeach
							</div>
						</div>
					</div>