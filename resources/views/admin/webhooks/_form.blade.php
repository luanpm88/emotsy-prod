{{ csrf_field() }}

<div class="row">
	<div class="col-md-10">
		<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
			<label class="fw-semibold">
				{{ trans('messages.webhook.webhook_name') }}
				<span class="text-danger">*</span>
			</label>

			@include('helpers.form_control.text', [
				'name' => 'webhook[name]',
				'value' => $webhook->name,
				'attributes' => [
					'required' => true,
				]
			])
		</div>

		<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
			<label class="fw-semibold">
				{{ trans('messages.webhook.select_event') }}
				<span class="text-danger">*</span>
			</label>

			@include('helpers.form_control.select', [
				'name' => 'webhook[event]',
				'value' => $webhook->event,
				'options' => array_map(function($event, $data) {
					return [
						'value' => $event,
						'text' => trans('messages.webhook.event.' . $event),
					];
				}, array_keys(config('webhooks')), config('webhooks')),
				'attributes' => [
					'required' => 'required',
				],
			])
		</div>
	</div>
</div>
