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
                <h2 class="mr-auto">{{ trans('messages.automation.classic_builder') }}</h2>
                <a href="{{ action('Automation2Controller@templateEditPlain', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}" class="btn btn-default plain-edit">
                    {{ trans('messages.email.plain_text_editor') }}
                </a>
            </div>

            
            <form action="{{ action('Automation2Controller@templateEditClassic', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}" method="POST" class="ajax_upload_form builder-classic-form form-validate-jquery">
                {{ csrf_field() }}
                
                @include('helpers.form_control', [
                    'class' => 'template-editor',
                    'required' => true,
                    'type' => 'textarea',
                    'name' => 'content',
                    'value' => $email->render(),
                    'rules' => ['content' => 'required']
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

        tinymce.init({
            selector: '.template-editor',
            height: 500,
            convert_urls: false,
            remove_script_host: false,
            forced_root_block: "",
            plugins: [
            'fullpage table advlist autolink lists link image charmap print preview anchor directionality',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media contextmenu paste code textcolor'
            ],
            toolbar: 'tags insertfile undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | ltr rtl',
            valid_elements : '*[*],meta[*]',
            extended_valid_elements : "meta[*]",
            valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
            content_css: [
                APP_URL.replace('/index.php','')+'/tinymce/skins/lightgray/content.fixed.css',
            ],
            external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager/plugin.min.js"},
            setup: function(editor) {
                editor.addButton('tags', {
                    type: 'listbox',
                    text: '{{ trans('messages.editor.insert_tag') }}',
                    icon: false,
                    onselect: function(e) {                        
                        editor.insertContent(this.value());
                        $('#mceu_0 .mce-txt').html('{{ trans('messages.editor.insert_tag') }}');
                    },
                    values: [
                        @foreach(Acelle\Model\Template::tags() as $tag)
                            {text: '{{ "{".$tag["name"]."}" }}', value: '{{ "{".$tag["name"]."}" }}'},
                        @endforeach
                    ],
                    onPostRender: function() {
                        // Select the firts item by default
                        this.value('JustifyLeft');
                    }
                });
            }
        });
    
    </script>
@endsection
