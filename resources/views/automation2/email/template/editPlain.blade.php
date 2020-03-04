@extends('layouts.empty')

@section('title', trans('messages.edit_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('content')    
    <div class="row" style="width: 100%; padding: 40px">
        <div class="col-md-1"></div>
        <div class="col-md-10">

            <div class="d-flex align-items-canter">
                <h2 class="mr-auto">{{ trans('messages.email.plain_text_editor') }}</h2>
                <a href="{{ action('Automation2Controller@templateEditClassic', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}" class="btn btn-default plain-edit">
                    {{ trans('messages.email.html_editor') }}
                </a>
            </div>

            
            <form action="{{ action('Automation2Controller@templateEditPlain', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}" method="POST" class="ajax_upload_form builder-classic-form form-validate-jquery">
                {{ csrf_field() }}
                
                @include('helpers.form_control', [
                    'class' => 'campaign-plain-text',
                    'required' => true,
                    'type' => 'textarea',
                    'name' => 'plain',
                    'value' => $email->plain,
                    'rules' => ['plain' => 'required']
                ])
                
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags($automation->mailList)])
                
                <hr>
                <div class="text-right">
                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                    <a href="javascript:;" class="btn bg-grey-800 close-button"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                </div>
                
            </form>  
            
        </div>
    </div>

    <script>
    
        $('.close-button').click(function() {
            parent.$('.full-iframe-popup').remove();
            popup.load();
        });

        $('.builder-classic-form').submit(function(e) {
            e.preventDefault();

            tinymce.triggerSave();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            if ($(this).valid()) {
                // open builder effects
                addMaskLoading("{{ trans('messages.automation.template.saving') }}", function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        statusCode: {
                            // validate error
                            400: function (res) {
                                console.log('Something went wrong!');
                            }
                        },
                        success: function (response) {
                            removeMaskLoading();

                            // notify
                            parent.notify('success', '{{ trans('messages.notify.success') }}', response.message);

                            parent.$('.full-iframe-popup').remove();
                            parent.popup.load();
                        }
                    });
                });         
            }   
        });
    
    </script>
@endsection
