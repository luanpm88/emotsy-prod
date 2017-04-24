<div class="row">

</div>

<h4 class="text-semibold text-teal-800">{{ trans('messages.sending_quota') }}</h4>

<div class="row">
	<div class="col-md-12">
		<p>{!! trans('messages.options.wording') !!}</p>
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', [
			'type' => 'text',
			'class' => 'numeric',
			'name' => 'quota_value',
			'value' => $server->quota_value,
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', [
			'type' => 'text',
			'class' => 'numeric',
			'name' => 'quota_base',
			'value' => $server->quota_base,
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
	<div class="col-md-4">
		@include('helpers.form_control', ['type' => 'select',
			'name' => 'quota_unit',
			'value' => $server->quota_unit,
			'label' => trans('messages.quota_time_unit'),
			'options' => Acelle\Model\EmailVertificationServer::quotaTimeUnitOptions(),
			'include_blank' => trans('messages.choose'),
			'help_class' => 'email_verification_server',
			'rules' => $server->rules()
		])
	</div>
</div>

<hr >
<div class="text-left">
	<button class="btn bg-teal mr-5"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
	<a href="{{ action('Admin\EmailVertificationServerController@index') }}" type="button" class="btn bg-grey">
		<i class="icon-cross2"></i> {{ trans('messages.cancel') }}
	</a>
</div>
