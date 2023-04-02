@extends('layouts.popup.small')

@section('title')
    <span class="material-symbols-rounded me-1 text-muted2">content_copy</span>
    {!! trans('messages.automation.copy.title', [
        'name' => $automation->name
    ]) !!}
@endsection

@section('content')
    <form id="copyAutomationForm"
        action="{{ action('Automation2Controller@copy', $automation->uid) }}"
        method="POST"
    >
        {{ csrf_field() }}  

        <p class="">{{ trans('messages.automation.copy.wording') }}</p>

        @include('helpers.form_control', [
            'type' => 'text',
            'label' => trans('messages.automation.name'),
            'name' => 'name',
            'value' => $newAutomation->name,
            'help_class' => 'list',
            'rules' => ['name' => 'required'],
        ])

        @include('helpers.form_control', [
            'name' => 'mail_list_uid',
            'type' => 'select',
            'label' => trans('messages.list'),
            'value' => $newAutomation->mailList->uid,
            'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
            'rules' => ['mail_list_uid' => 'required'],
        ])

        <div class="mt-4 text-center">
            <button id="copyAutomationButton" type="submit" class="btn btn-secondary me-1">{{ trans('messages.automation.duplicate') }}</button>
            <button type="button" class="btn btn-link fw-600" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
        </div>
    </form>


    <script>
        var Automation2Copy = {
            copy: function(url, data) {
                Automation2List.copyPopup.mask();
                addButtonMask($('#copyAutomationButton'));

                // copy
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    globalError: false
                }).done(function(response) {
                    notify({
                        type: 'success',
                        message: response,
                    });

                    Automation2List.copyPopup.hide();
                    Automation2Index.getList().load();

                }).fail(function(jqXHR, textStatus, errorThrown){
                    // for debugging
                    Automation2List.copyPopup.loadHtml(jqXHR.responseText);
                }).always(function() {
                    Automation2List.copyPopup.unmask();
                    removeButtonMask($('#copyAutomationButton'));
                });
            }
        }

        $(function() {
            $('#copyAutomationForm').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();

                Automation2Copy.copy(url, data);
            });
        });
    </script>
@endsection