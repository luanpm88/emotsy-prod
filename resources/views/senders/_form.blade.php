<div class="row">
	<div class="col-md-6">
		<div class="sub_section">
			@include('helpers.form_control', [
				'type' => 'text',
				'name' => 'name',
				'value' => $sender->name,
				'help_class' => 'sender',
				'rules' => $sender->rules()
			])

			@include('helpers.form_control', [
				'type' => 'text',
				'name' => 'email',
				'value' => $sender->email,
				'help_class' => 'sender',
				'rules' => $sender->rules()
			])

		</div>
		<div class="text-left">
			<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
		</div>
	</div>
</div>
