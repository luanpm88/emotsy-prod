<div class="row">
	<div class="col-md-6">
        @include('helpers.form_control', [
            'type' => 'textarea',
            'class' => 'setting-editor',
            'name' => 'options[notice]',
            'value' => $gateway['fields']['notice'],
            'label' => trans('messages.payment.direct.notice'),
            'help_class' => 'payment',
            'rules' => ['options.notice' => 'required'],
        ])
    </div>
</div>