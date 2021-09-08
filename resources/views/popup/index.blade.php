<!doctype html>
<html>
    <head>
        <title>Form Builder</title>
        <meta charset="utf-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        @include('layouts.core._favicon')
        
        <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

        <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"> 


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
                    url: '{{ action('PopupController@content', [
                        'template_uid' => $templates[0]->uid,
                    ]) }}',
                    saveUrl: '',
                    saveMethod: 'POST',
                    uploadAssetUrl: '',
                    uploadAssetMethod: 'POST',
                    backCallback: function() {
                        
                    },

                    renderCanvas: function() {
                        return $('#formCanvas');
                    },
                    renderWidgetPanel: function() {
                        return $('#formWidgetPanel');
                    },

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
                            manager.groups.forEach(function(group) {
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
        <div class="acelle-top-menu">
            <nav class="navbar fixed-top navbar-light bg-light shadow-sm">
                <a class="navbar-brand d-flex align-items-center pl-0" href="{{ action('Admin\HomeController@index') }}">
                    <span class="material-icons-outlined mr-2">
                        dashboard
                    </span>
                    Popup-Form Builder
                </a>
                <a class="navbar-brand d-flex align-items-center mr-0" href="{{ action('HomeController@index') }}"><span class="material-icons-outlined">
                    close
                    </span></a>
            </nav>
        </div>
        <div class="d-flex">
            <div>
                <div class="browser-backdrop">
                    <div class="browser-head d-flex align-items-center shadown">
                        <div>
                            <div class="control-dots d-flex align-items-center">
                                <div class="control-dot"></div>
                                <div class="control-dot orange"></div>
                                <div class="control-dot green"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="formCanvas"></div>
            </div>
            <div id="formWidgetPanel"></div>
        </div>

        <div class="styles-settings shadow-sm">
            <div class="styles-sections">
                <div class="styles-section form-layouts">
                    <a href="javascript:;" class="styles-toggle px-2 py-1">
                        <span class="material-icons-outlined" style="line-height:30px">
                            dashboard
                        </span>
                    </a>
                    <div class="styles-container shadow rounded-lg overflow-hidden p-4">
                        <div class="d-flex align-items-center styles-container-heading shadow">
                            <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                                <span class="material-icons-outlined" style="line-height:30px">
                                    keyboard_backspace
                                </span>
                            </a>
                            <h6 class="d-inline ml-2 m-0">Layouts</h6>
                        </div>  
                        <hr>
                        <div class="">
                            @foreach ($templates->take(5) as $template)
                                <a href="{{ action('PopupController@content', [
                                    'template_uid' => $template->uid,
                                ]) }}" class="style-item d-block rounded-3 overflow-hidden mb-4 shadow-sm">
                                    <img src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                                </a>
                            @endforeach
                        </div>
                    </div>  
                </div>
                <div class="styles-section form-theme">
                    <a href="javascript:;" class="styles-toggle px-2 py-1">
                        <span class="material-icons-outlined" style="line-height:30px">
                            web
                        </span>
                    </a>
                    <div class="styles-container shadow rounded-lg overflow-hidden p-4">    
                        <div class="d-flex align-items-center styles-container-heading shadow-sm">
                            <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                                <span class="material-icons-outlined" style="line-height:30px">
                                    keyboard_backspace
                                </span>
                            </a>
                            <h6 class="d-inline ml-2 m-0">Themes</h6>
                        </div>                    
                            
                        <hr>
                        <div class="">
                            @foreach ($templates->skip(5) as $template)
                                <a href="{{ action('PopupController@content', [
                                    'template_uid' => $template->uid,
                                ]) }}" class="style-item d-block rounded-3 overflow-hidden mb-4 shadow-sm">
                                    <img src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                                </a>
                            @endforeach
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <style>
            .lds-dual-ring {
                display: inline-block;
                width: 80px;
                height: 80px;
            }
            .lds-dual-ring:after {
                content: " ";
                display: block;
                width: 30px;
                height: 30px;
                margin: 4px;
                border-radius: 80%;
                border: 2px solid #aaa;
                border-color: #007bff transparent #007bff transparent;
                animation: lds-dual-ring 1.2s linear infinite;
            }
            @keyframes lds-dual-ring {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
        

        <script>
            var formBuilder = {
                currentFormHtml: null,

                loadUrl: function(url) {
                    // save current form container
                    editor.cleanUpContent();
                    formBuilder.currentFormHtml = editor.getIframeContent().find('#formContainer').html();

                    editor.loadUrl(url, function() {
                        // load saved form container to new template
                        editor.getIframeContent().find('#formContainer').append(formBuilder.currentFormHtml);
                        setTimeout(function() {
                            editor.adjustIframeSize();
                        }, 100);
                    });
                }
            }

            var manager;
            $(function() {

                manager = new GroupManager();

                $('.styles-settings .styles-section').each(function() {
                    manager.add({
                        box: $(this),
                        button: $(this).find('.styles-toggle'),
                        container: $(this).find('.styles-container'),
                        back: $(this).find('.styles-back')
                    });
                });

                manager.bind(function(group, others) {
                    // show
                    group.show = function() {
                        // hide others
                        others.forEach(function(other) {
                            other.hide();
                        });

                        group.container.addClass('show');
                        $('.styles-settings').addClass('open');

                        group.container.scrollTop(0);
                    };

                    // hide
                    group.hide = function() {
                        group.container.removeClass('show');
                        $('.styles-settings').removeClass('open');
                    }

                    group.button.on('click', function() {
                        // toggle container
                        group.show();                       
                    });

                    group.back.on('click', function() {
                        // toggle container
                        group.hide();                       
                    });
                });

                // click outside
                $(document).on('mouseup', function(e) {
                    var container = $('.styles-container');
                    // if the target of the click isn't the container nor a descendant of the container
                    if (!container.is(e.target) && container.has(e.target).length === 0) 
                    {
                        manager.groups.forEach(function(group) {
                            group.hide();
                        });
                    }
                });

                // remove loadding effect
                $('.lds-dual-ring').remove();

                $('.style-item').on('click', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');

                    formBuilder.loadUrl(url);
                });
            })
        </script>
  </body>
</html>
