@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.test_sending_server') }}
@endsection

@section('content')
        <form id="TestEmailForm" action="" method="POST" class="ajax_upload_form form-validate-jquery">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="">
            <input type="hidden" name="uids" value="">

            @foreach (request()->all() as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <div class="">
                <p>{{ trans('messages.test_sending_server.intro') }}</p>
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => 'email',
                    'label' => trans('messages.from_email'),
                    'name' => 'from_email',
                    'value' => '',
                    'help_class' => 'sending_server',
                    'rules' => ['from_email' => 'required']
                ])
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => 'email',
                    'label' => trans('messages.to_email'),
                    'name' => 'to_email',
                    'value' => '',
                    'help_class' => 'sending_server',
                    'rules' => ['to_email' => 'required']
                ])
                @include('helpers.form_control', [
                    'type' => 'text',
                    'class' => '',
                    'label' => trans('messages.subject'),
                    'name' => 'subject',
                    'value' => '',
                    'help_class' => 'sending_server',
                    'rules' => ['subject' => 'required']
                ])
                @include('helpers.form_control', [
                    'type' => 'textarea',
                    'class' => '',
                    'label' => trans('messages.content'),
                    'name' => 'content',
                    'value' => '',
                    'help_class' => 'sending_server',
                    'rules' => ['content' => 'required']
                ])
            </div>
            <div class="mt-4 text-center">
                <button type="submit"
                    href="{{ action('SendingServerController@test', $server->uid) }}"
                    role="button"
                    class="btn btn-secondary me-1 ajax_link"
                    data-in-form="true"
                    link-method="POST"
                    mask-title="{{ trans('messages.sending_server.testing_connection') }}"
                >
                    {{ trans('messages.send') }}
                </button>
                <button role="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.close') }}</button>
            </div>
        </form>

        <script>
            var TestEmail = {
                url: '{{ action('SendingServerController@test', $server->uid) }}',
                getData: function() {
                    return $('#TestEmailForm').serialize();
                },
    
                run: function() {
                    SendTestEmail.getPopup().mask();
    
                    // copy
                    $.ajax({
                        url: this.url,
                        type: 'POST',
                        data: this.getData(),
                        globalError: false
                    }).done(function(response) {
                        new Dialog('alert', {
                            title: LANG_SUCCESS,
                            message: response.message,
                        });
    
                        SendTestEmail.getPopup().unmask();
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        // for debugging
                        new Dialog('alert', {
                            title: LANG_ERROR,
                            message: JSON.parse(jqXHR.responseText).message,
                        });
                        
                        SendTestEmail.getPopup().unmask();
                    }).always(function() {
                        SendTestEmail.getPopup().unmask();
                    });
                }
            }
    
            $(function() {
                $('#TestEmailForm').on('submit', function(e) {
                    e.preventDefault();
    
                    if ($(this).valid()) {
                        TestEmail.run();
                    }
                    
                    return false;
                });
            })
        </script>
@endsection