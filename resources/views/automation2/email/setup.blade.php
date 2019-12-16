@extends('layouts.popup.medium')

@section('content')
    <p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
        
    @include('automation2.email._tabs', ['tab' => 'setup'])
        
    <h3 class="mb-4">Email Setup</h3>    
    <p>Please fill-up email information below. They will be used to apply to all emails that send to customers.</p>
    
    <form id="emailSetup" action="{{ action('Automation2Controller@emailSetup', $automation->uid) }}" method="POST">
        {{ csrf_field() }}
        
        <input type="hidden" name="email_uid" value="{{ $email->uid }}" />
    
        <div class="row">
            <div class="col-md-6">                                            
                @include('helpers.form_control', ['type' => 'text',
                    'name' => 'subject',
                    'label' => trans('messages.email_subject'),
                    'value' => $email->subject,
                    'rules' => $email->rules(),
                    'help_class' => 'email'
                ])
                                                
                @include('helpers.form_control', ['type' => 'text',
                    'name' => 'from_name',
                    'label' => trans('messages.from_name'),
                    'value' => $email->from_name,
                    'rules' => $email->rules(),
                    'help_class' => 'email'
                ])
                
                @include('helpers.form_control', [
                    'type' => 'autofill',
                    'id' => 'sender_from_input',
                    'name' => 'from',
                    'label' => trans('messages.from_email'),
                    'value' => $email->from,
                    'rules' => $email->rules(),
                    'help_class' => 'email',
                    'url' => action('SenderController@dropbox'),
                    'empty' => trans('messages.sender.dropbox.empty'),
                    'error' => trans('messages.sender.dropbox.error', [
                        'sender_link' => action('SenderController@index'),
                    ]),
                    'header' => trans('messages.verified_senders'),
                ])
                                                
                @include('helpers.form_control', ['type' => 'text',
                    'name' => 'reply_to',
                    'label' => trans('messages.reply_to'),
                    'value' => $email->reply_to,
                    'rules' => $email->rules(),
                    'help_class' => 'email'
                ])
                
            </div>
            <div class="col-md-6 segments-select-box">
                <div class="form-group checkbox-right-switch">
                    @include('helpers.form_control', ['type' => 'checkbox3',
                        'name' => 'track_open',
                        'label' => trans('messages.automation.email.track_open'),
                        'value' => $email->track_open,
                        'options' => [false,true],
                        'help_class' => 'email',
                        'rules' => $email->rules(),
                    ])
                
                    @include('helpers.form_control', ['type' => 'checkbox3',
                        'name' => 'track_click',
                        'label' => trans('messages.automation.email.track_click'),
                        'value' => $email->track_click,
                        'options' => [false,true],
                        'help_class' => 'email',
                        'rules' => $email->rules(),
                    ])
                    
                    @include('helpers.form_control', ['type' => 'checkbox3',
                        'name' => 'sign_dkim',
                        'label' => trans('messages.automation.email.add_sign_dkim'),
                        'value' => $email->sign_dkim,
                        'options' => [false,true],
                        'help_class' => 'email',
                        'rules' => $email->rules(),
                    ])
                </div>
            </div>
        </div>
        
        <div class="text-right mt-5">
            <button class="btn btn-secondary">
                <span class="d-flex align-items-center">
                    <span>{{ trans('messages.email.setup.save_next') }}</span> <i class="material-icons">keyboard_arrow_right</i>
                </span>
            </button>
        </div>
    </form>
    
    <script>
        // auto fill
        var box = $('#sender_from_input').autofill({
            messages: {
                header_found: '{{ trans('messages.sending_identity') }}',
                header_not_found: '{{ trans('messages.sending_identity.not_found.header') }}'
            }
        });
        box.loadDropbox(function() {
            $('#sender_from_input').focusout();
            box.updateErrorMessage();
        })
        
        $('#emailSetup').submit(function(e) {
            e.preventDefault();
            
            var form = $(this);
            var url = form.attr('action');
            
            console.log(url);
            
            // loading effect
            popup.loading();
            
            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                statusCode: {
                    // validate error
                    400: function (res) {
                       popup.loadHtml(res.responseText);
                    }
                 },
                 success: function (response) {
                    popup.load(response.url);
                    
                    if (response.is_new) {
                        var newE = new ElementAction({title: response.title, options: response.options});
						tree.getSelected().insert(newE);
                    } else {
                        // set node title
						tree.getSelected().setTitle(response.title);
						// merge options with reponse options
						tree.getSelected().setOptions($.extend(tree.getOptions(), response.options));
                        
                        // reload sidebar
                        sidebar.load();
                    }
                    
                    // save tree
					saveData();
                    
                    notify('success', '{{ trans('messages.notify.success') }}', response.message);
                 }
            });
        });
    </script>
@endsection