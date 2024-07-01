@foreach ($list->getFields as $field)
	@if ($field->visible || !isset($is_page))
		@if ($field->tag != 'EMAIL')
			@if ($field->type == "text")
				@include('helpers.form_control', ['type' => $field->type, 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "number")
				@include('helpers.form_control', ['type' => 'number', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "textarea")
				@include('helpers.form_control', ['type' => 'textarea', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "dropdown")
				@include('helpers.form_control', ['type' => 'select', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "multiselect")
				@include('helpers.form_control', ['multiple' => true, 'type' => 'select', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "checkbox")
				@include('helpers.form_control', ['multiple' => true, 'type' => 'checkboxes', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "radio")
				@include('helpers.form_control', ['multiple' => true, 'type' => 'radio', 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "date")
				@include('helpers.form_control', ['multiple' => true, 'type' => 'date', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "datetime")
				@include('helpers.form_control', ['multiple' => true, 'type' => 'datetime', 'name' => $field->tag . "[]", 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'options' => $field->getSelectOptions(), 'rules' => $list->getFieldRules()])
			@elseif ($field->type == "phone")
				@php
					$value = (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value);
				@endphp
				<link
					rel="stylesheet"
					href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
				/>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

				<div class="form-group {{ $errors->has('credits') ? 'has-error' : '' }}">
					<label>
						{{ trans('messages.phone') }}
					</label>
					<div>
						<input
							{{ isset($list->getFieldRules()[$field->tag]) && $list->getFieldRules()[$field->tag] == 'required' ? 'required' : '' }}
							id="phone_{{ $field->uid }}_helper"
							type="text"
							name="{{ $field->tag }}_Helper" class="form-control"
							value="{{ $value }}"
						>
						
						<input id="phone_{{ $field->uid }}" type="hidden" name="{{ $field->tag }}" value="{{ $value }}" />
					</div>
					@if ($errors->has($field->tag))
						<div class="help-block">
							{{ $errors->first($field->tag) }}
						</div>
					@endif
				</div>

				<script>
					const phoneInputFieldHelper = document.querySelector("#phone_{{ $field->uid }}_helper");
					const phoneInputField = document.querySelector("#phone_{{ $field->uid }}");
					const phoneInput = window.intlTelInput(phoneInputFieldHelper, {
						initialValue: '{{ $value }}',
						utilsScript:
							"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
					});

					$(function() {
						$(phoneInputField).closest('form').on('submit', function(e) {
							$(phoneInputField).val(phoneInput.getNumber());
						});
					});
				  </script>
			@endif
		@else
			@include('helpers.form_control', ['type' => $field->type, 'name' => $field->tag, 'label' => $field->label, 'value' => (isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value), 'rules' => $list->getFieldRules()])
		@endif
	@endif
@endforeach