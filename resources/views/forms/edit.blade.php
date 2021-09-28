<!doctype html>
<html>
    <head>
        <title>{{ trans('messages.form.builder') }} - {{ $form->name }}</title>
        <meta charset="utf-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        @include('layouts.core._favicon')
        
        <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

        <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"> 

        <link rel="stylesheet" href="{{ URL::asset('core/slider/bootstrap-slider.min.css') }}" />
        <script type="text/javascript" src="{{ URL::asset('core/slider/bootstrap-slider.min.js') }}"></script>

        <link type="text/css" rel="stylesheet" href="{{ URL::asset('core/css/form-builder.css') }}" />

        <script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('core/js/validate.js') }}"></script>


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
                <span class="navbar-brand d-flex align-items-center pl-0">
                    <span class="material-icons-outlined mr-2">
                        dashboard
                    </span>
                    {{ trans('messages.form.builder') }}
                </span>
                <a class="navbar-brand d-flex align-items-center mr-0" href="{{ action('FormController@index') }}"><span class="material-icons-outlined">
                    close
                    </span></a>
            </nav>
        </div>
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
                            <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.layouts') }}</h6>
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
                            <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.themes') }}</h6>
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
                <div class="styles-section form-theme">
                    <a href="javascript:;" class="styles-toggle px-2 py-1">
                        <span class="material-icons-round" style="line-height:30px">
                            tune
                        </span>
                    </a>
                    <div class="styles-container shadow rounded-lg overflow-hidden p-4" style="overflow-x:hidden!important; height:auto">    
                        <div class="d-flex align-items-center styles-container-heading shadow-sm">
                            <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                                <span class="material-icons-outlined" style="line-height:30px">
                                    keyboard_backspace
                                </span>
                            </a>
                            <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.display_settings') }}</h6>
                        </div>                    
                            
                        <hr>
                        <div class="" style="width:201px">
                            <form id="FormDisplaySetting" action="{{ action('FormController@settings', [
                                'uid' => $form->uid
                            ]) }}" method="POST">
                                {{ csrf_field() }}

                                <div class="mb-2">
                                    <label class="mb-2 fs-7">{{ trans('messages.form.overlay_opacity') }}</label>
                                    <input id="opacityOverlay" name="overlay_opacity"
                                        type="text"
                                        data-slider-min="0"
                                        data-slider-max="100"
                                        data-slider-step="1"
                                        data-slider-value="{{ $form->getMetadata('element_id') ? $form->getMetadata('overlay_opacity') : '5'}}"
                                    />
                                    <script>
                                        $(function() {
                                            // With JQuery
                                            $('#opacityOverlay').slider();
                                        });
                                    </script>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 fs-7">{{ trans('messages.form.display') }}</label>
                                    <select class="form-control" name="display">
                                        <option value="immediately" {{ $form->getMetadata('display') == 'immediately' ? 'selected' : ''}}>{{ trans('messages.form.display.immediately') }}</option>
                                        <option value="wait" {{ $form->getMetadata('display') == 'wait' ? 'selected' : ''}}>{{ trans('messages.form.display.wait') }}</option>
                                        <option value="click" {{ $form->getMetadata('display') == 'click' ? 'selected'  : ''}}>{{ trans('messages.form.display.click') }}</option>
                                    </select>
                                </div>
                                
                                <div class="display-select-wait mb-3">
                                    <div class="">
                                        <label class="mb-2 fs-7">{{ trans('messages.form.display.wait_time') }}</label>
                                        <input type="number" min="1" class="form-control text-right" required name="wait_time"
                                            value="{{ $form->getMetadata('wait_time') ? $form->getMetadata('wait_time') : '5'}}" />
                                    </div>
                                </div>

                                <div class="display-select-element mb-3">
                                    <label class="mb-2 fs-7">{{ trans('messages.form.display.element_id') }}</label>
                                    <input type="text" value="{{ $form->getMetadata('element_id') ? $form->getMetadata('element_id') : ''}}"
                                        class="form-control" name="element_id" required="required" placeholder="Ex: BuyButton" />
                                </div>

                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary display-settings-save">{{ trans('messages.save') }}</button>
                                </div>
                            </form>
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
            var FormBuilder = {
                currentFormHtml: null,
                styleManager: null,
                displayOption: null,

                // STYLE MANAGER
                getStyleManager: function() {
                    var _this = this;

                    if (_this.styleManager == null) {
                        _this.styleManager = new GroupManager();

                        $('.styles-settings .styles-section').each(function() {
                            _this.styleManager.add({
                                box: $(this),
                                button: $(this).find('.styles-toggle'),
                                container: $(this).find('.styles-container'),
                                back: $(this).find('.styles-back')
                            });
                        });

                        _this.styleManager.bind(function(group, others) {
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
                                _this.styleManager.groups.forEach(function(group) {
                                    group.hide();
                                });
                            }
                        });
                    }
                    return this.styleManager;
                },

                // STYLE MANAGER
                getDisplayManager: function() {
                    var _this = this;

                    if (_this.displayManager == null) {
                        _this.displayManager = new GroupManager();

                        _this.displayManager.add({
                            box: $('.display-select-element'),
                            value: 'click',
                            selectedValue: function() {
                                return $('[name="display"]').val();
                            }
                        });

                        _this.displayManager.add({
                            box: $('.display-select-wait'),
                            value: 'wait',
                            selectedValue: function() {
                                return $('[name="display"]').val();
                            }
                        });

                        _this.displayManager.bind(function(group) {
                            group.check = function() {
                                if (group.selectedValue() == group.value) {
                                    group.box.show();
                                } else {
                                    group.box.hide();
                                }
                            }

                            $('[name="display"]').on('change', function() {
                                group.check();
                            });

                            group.check();
                        });                        
                    }
                    return this.styleManager;
                },

                saveDisplaySetting: function() {
                    var form = $('#FormDisplaySetting');
                    var url = form.attr('action');
                    var data = form.serialize();

                    if (form.valid()) {
                        // copy
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: data
                        }).done(function(response) {
                            div = $('<div class="alert alert-success mt-3 saved-alert">Form display settings was saved.</div>');
                            form.append(div);

                            setTimeout(function() {
                                div.remove();
                            }, 2000);
                        });
                    }
                },

                loadUrl: function(url) {
                    // save current form container
                    editor.cleanUpContent();
                    FormBuilder.currentFormHtml = editor.getIframeContent().find('#formContainer').html();

                    editor.loadUrl(url, function() {
                        // load saved form container to new template
                        editor.getIframeContent().find('#formContainer').append(FormBuilder.currentFormHtml);
                        setTimeout(function() {
                            editor.adjustIframeSize();
                        }, 100);
                    });
                }
            }
            
            $(function() {
                FormBuilder.getStyleManager();
                FormBuilder.getDisplayManager();
                

                // remove loadding effect
                $('.lds-dual-ring').remove();

                $('.style-item').on('click', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');

                    FormBuilder.loadUrl(url);
                });

                // display setting save
                $('.display-settings-save').on('click', function() {
                    FormBuilder.saveDisplaySetting();
                });
            })
        </script>
  </body>
</html>
