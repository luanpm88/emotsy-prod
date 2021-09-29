<!doctype html>
<html>
    <head>
        <title>{{ trans('messages.form.builder') }} - {{ $form->name }}</title>
        <meta charset="utf-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        @include('layouts.core._favicon')
        
        <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"> 

        <script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('core/js/validate.js') }}"></script>

        <link type="text/css" rel="stylesheet" href="{{ URL::asset('core/css/form-builder.css') }}" />

        <script>
            var CSRF_TOKEN = "{{ csrf_token() }}";
            var editor;
            var formFields = {!! json_encode($formFields) !!};
            
            $( document ).ready(function() {
                editor = new Editor({
                    strict: true,
                    showHelp: false,
                    showInlineToolbar: false,

                    formFields: formFields,
                    
                    root: '{{ URL::asset('builder') }}/',                
                    lang: {!! json_encode(language()->getBuilderLang()) !!},
                    url: '{{ action('FormController@content', [
                        'uid' => $form->uid,
                    ]) }}',
                    saveUrl: '',
                    saveMethod: 'POST',
                    uploadAssetUrl: '',
                    uploadAssetMethod: 'POST',
                    backCallback: function() {
                        
                    },

                    canvas: '#formCanvas',
                    sidePanel: '#formWidgetPanel',

                    tags: [],

                    filemanager: '{{ URL::asset('filemanager2/dialog.php') }}',
                    logo: '{{ \Acelle\Model\Setting::get('site_logo_small') ? action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) : URL::asset('images/logo_light_blue.svg') }}',
                    backgrounds: [
                        '{{ url('/images/backgrounds/images1.jpg') }}',
                        '{{ url('/images/backgrounds/images2.jpg') }}',
                        '{{ url('/images/backgrounds/images3.jpg') }}',
                        '{{ url('/images/backgrounds/images4.png') }}',
                        '{{ url('/images/backgrounds/images5.jpg') }}',
                        '{{ url('/images/backgrounds/images6.jpg') }}',
                        '{{ url('/images/backgrounds/images9.jpg') }}',
                        '{{ url('/images/backgrounds/images11.jpg') }}',
                        '{{ url('/images/backgrounds/images12.jpg') }}',
                        '{{ url('/images/backgrounds/images13.jpg') }}',
                        '{{ url('/images/backgrounds/images14.jpg') }}',
                        '{{ url('/images/backgrounds/images15.jpg') }}',
                        '{{ url('/images/backgrounds/images16.jpg') }}',
                        '{{ url('/images/backgrounds/images17.png') }}'
                    ],
                    loaded: function() {
                        if (!editor.getIframeContent().find('#formContainer').children().length) {
                            // add default widget
                            formFields.forEach(function(field) {
                                if (field.visible == 1) {
                                    // create widget from field info
                                    var className = field.type.charAt(0).toUpperCase() + field.type.slice(1) + 'FieldWidget';
                                    var widget = eval('new ' + className + '(field)');
                                    // insert widget content to editor
                                    editor.getIframeContent().find('#formContainer').append(widget.getContentHtml());   
                                }                   
                            });

                            // Add submit button
                            var widget = new SubmitButtonWidget();
                            // insert widget content to editor
                            editor.getIframeContent().find('#formContainer').append(widget.getContentHtml());
                        }

                        // js auto height iframe
                        editor.adjustIframeSize();

                        // hide styles
                        $("#builder_iframe").contents().click(function(e) {
                            FormsBuilder.getFormsEdit().getStyleManager().groups.forEach(function(group) {
                                group.hide();
                            });
                        });

                        //
                        editor.addCustomCss('{{ URL::asset('core/css/form-builder-frame.css') }}');
                    }
                });
            
                editor.init();
            });
        </script>
    </head>
    <body>        
        <div class="">
            <div>
                <div class="browser-backdrop">
                    <div class="browser-frame">
                        <div class="browser-head d-flex align-items-center shadow">
                            <div>
                                <div class="control-dots d-flex align-items-center">
                                    <div class="control-dot"></div>
                                    <div class="control-dot orange"></div>
                                    <div class="control-dot green"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center px-5" style="width: 100%">
                                <input class="form-control browser-address fs-3 px-4 rounded-pill" value="https://your-site.com" style="" />
                            </div>
                        </div>
                        <div id="formCanvas"></div>
                    </div>
                </div>
            </div>
            <div id="formWidgetPanel"></div>
        </div>    
        
        <script>
            var FormsBuilder = {
                getFormsEdit: function() {
                    return parent.FormsEdit;
                }
            };

            $(function() {
                // hide menu
                $(document).on('click', function() {
                    FormsBuilder.getFormsEdit().getStyleManager().groups.forEach(function(group) {
                        group.hide();
                    });
                });
            });
        </script>
  </body>
</html>
