<div class="mb-20">
    <input type="hidden" name="options[type]" value="datetime" />

    @include('helpers.form_control', [
        'type' => 'date2',
        'class' => '',
        'label' => trans('messages.automation.date'),
        'name' => 'options[date]',
        'value' => toDateString(\Carbon\Carbon::now()),
        'help_class' => 'trigger',
    ])
    
    @include('helpers.form_control', [
        'type' => 'time2',
        'name' => 'options[at]',
        'label' => trans('messages.automation.at'),
        'value' => toTimeString(\Carbon\Carbon::now()),
        'help_class' => 'trigger'
    ])

    @include('helpers.form_control', [
        'name' => 'mail_list_uid',
        'include_blank' => trans('messages.automation.choose_list'),
        'type' => 'select',
        'label' => trans('messages.list'),
        'value' => '',
        'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
    ])
</div>