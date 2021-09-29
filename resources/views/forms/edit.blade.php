<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
	
	@include('layouts.core._head')
	@include('layouts.core._script_vars')

    <link rel="stylesheet" href="{{ URL::asset('core/slider/bootstrap-slider.min.css') }}" />
    <script type="text/javascript" src="{{ URL::asset('core/slider/bootstrap-slider.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>

    <link type="text/css" rel="stylesheet" href="{{ URL::asset('core/css/form-builder.css') }}" />
</head>

<body class="" style="overflow: hidden">

	<div class="acelle-top-menu">
        <nav class="navbar fixed-top navbar-light bg-light shadow-sm px-4 py-0">
            <span class="navbar-brand d-flex align-items-center pl-0">
                <span class="material-icons-outlined mr-2">
                    dashboard
                </span>
                {{ trans('messages.form.builder') }}
            </span>
            <div class="ms-auto me-4">
                <button class="me-2 builder-save-button btn btn-secondary" href="{{ action('FormController@index') }}">
                    {{ trans('messages.form.builder.save') }}
                </button>
            </div>
            <div>
                
                <a class="fs-4 builder-exit-button" href="{{ action('FormController@index') }}"><span class="material-icons-round">
                    close
                    </span></a>
            </div>
                
        </nav>
    </div>
    <div class="">
        <iframe id="FormsEditIframe" scrolling="no" style="width: 100%;
        height: calc(100vh - 53px);border:none;overflow:hidden" src="{{ action('FormController@builder', [
            'uid' => $form->uid,
        ]) }}"></iframe>
    </div>

    <div class="styles-settings shadow-sm">
        <div class="styles-sections">
            <div class="styles-section form-layouts">
                <a href="javascript:;" class="styles-menu-button styles-toggle px-2 py-1">
                    <span class="material-icons-outlined" style="line-height:30px">
                        dashboard
                    </span>
                </a>
                <div class="styles-container shadow rounded overflow-hidden p-4">
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
                <a href="javascript:;" class="styles-menu-button styles-toggle px-2 py-1">
                    <span class="material-icons-outlined" style="line-height:30px">
                        web
                    </span>
                </a>
                <div class="styles-container shadow rounded overflow-hidden p-4">    
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
                <a href="javascript:;" class="styles-menu-button styles-toggle px-2 py-1">
                    <span class="material-icons-round" style="line-height:30px">
                        tune
                    </span>
                </a>
                <div class="styles-container shadow rounded overflow-hidden p-4" style="
                    overflow-x:hidden!important; height:auto;
                    max-height: calc(100vh - 100px);
                ">    
                    <div class="d-flex align-items-center styles-container-heading shadow-sm">
                        <a role="button" href="javascript:;" class="styles-back px-2 py-1">
                            <span class="material-icons-outlined" style="line-height:30px">
                                keyboard_backspace
                            </span>
                        </a>
                        <h6 class="d-inline ml-2 m-0">{{ trans('messages.form.form_settings') }}</h6>
                    </div>                    
                        
                    <hr>
                    <div class="" style="width:201px">
                        <form id="FormDisplaySetting" action="{{ action('FormController@settings', [
                            'uid' => $form->uid
                        ]) }}" method="POST">
                            {{ csrf_field() }}

                            <div class="mb-3">
                                @include('helpers.form_control', [
                                    'type' => 'text',
                                    'name' => 'name',
                                    'label' => trans('messages.name'),
                                    'value' => $form->name,
                                    'required' => true,
                                ])
                            </div>

                            <div class="mb-3">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'mail_list_uid',
                                    'label' => trans('messages.list'),
                                    'value' => $form->mailList->uid,
                                    'options' => Auth::user()->customer->readCache('MailListSelectOptions', []),
                                    'rules' => [],
                                ])
                            </div>

                            <div class="mb-2">
                                <label class="mb-2 fs-7">{{ trans('messages.form.overlay_opacity') }}</label>
                                <input id="opacityOverlay" name="overlay_opacity"
                                    type="text"
                                    data-slider-min="0"
                                    data-slider-max="100"
                                    data-slider-step="1"
                                    data-slider-value="{{ $form->getMetadata('overlay_opacity') ? $form->getMetadata('overlay_opacity') : '50'}}"
                                />
                                <script>
                                    $(function() {
                                        // With JQuery
                                        $('#opacityOverlay').slider();
                                    });
                                </script>
                            </div>

                            <div class="mb-3">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'display',
                                    'label' => trans('messages.form.display'),
                                    'value' => $form->getMetadata('display'),
                                    'options' => [
                                        ["value" => "immediately", "text" => trans('messages.form.display.immediately')],
                                        ["value" => "wait", "text" => trans('messages.form.display.wait')],
                                        ["value" => "click", "text" => trans('messages.form.display.click')],
                                    ],
                                ])
                            </div>
                            
                            <div class="display-select-wait mb-3">
                                <div class="">
                                    @include('helpers.form_control.number', [
                                        'name' => 'wait_time',
                                        'label' => trans('messages.form.display.wait_time'),
                                        'value' => $form->getMetadata('wait_time') ? $form->getMetadata('wait_time') : '5',
                                        'attributes' => [
                                            'class' => 'numeric',
                                            'min' => '1',
                                            'required' => 'required',
                                        ],
                                    ])
                                </div>
                            </div>

                            <div class="display-select-element mb-3">
                                @include('helpers.form_control.text', [
                                    'name' => 'element_id',
                                    'label' => trans('messages.form.display.element_id'),
                                    'value' => $form->getMetadata('element_id') ? $form->getMetadata('element_id') : '',
                                    'attributes' => [
                                        'required' => 'required',
                                    ],
                                ])
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary display-settings-save">{{ trans('messages.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>  
            </div>
            <div class="styles-section form-theme">
                <a href="javascript:;" class="styles-menu-button preview-popup px-2 py-1">
                    <span class="material-icons-outlined xtooltip" title="{{ trans('messages.preview') }}" style="line-height:30px">
                        visibility
                    </span>
                </a> 
            </div>
        </div>
    </div>

    <script>
        @include('forms.frontend.popupJs')
    </script>

	<script>
        var FormsEdit = {
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
                        notify({
                            message: response.message
                        });
                    });
                }
            },

            getEditor: function() {
                return document.getElementById('FormsEditIframe').contentWindow.editor;
            },

            getFormsBuilder: function() {
                return document.getElementById('FormsEditIframe').contentWindow.FormsBuilder;
            },

            loadUrl: function(url) {
                var _this = this;

                // save current form container
                _this.getEditor().cleanUpContent();

                _this.getEditor().loadUrl(url, function() {
                    _this.getEditor().adjustIframeSize();
                });
            },

            connectPopup: null,
            getConnectPopup: function() {
                if (this.connectPopup == null) {
                    this.connectPopup = new Popup({
                        url: '{{ action('FormController@connect', [
                            'uid' => $form->uid,
                        ]) }}'
                    });
                }
                return this.connectPopup;
            },

            preview: function() {
                popup = new AFormPopup({
                    url: '{{ action('FormController@frontendContent', [
                        'uid' => $form->uid,
                    ]) }}'
                });

                popup.load();
            }
        }
        
        $(function() {
            FormsEdit.getStyleManager();
            FormsEdit.getDisplayManager();
            

            // remove loadding effect
            $('.lds-dual-ring').remove();

            $('.style-item').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                FormsEdit.loadUrl(url);
            });

            // display setting save
            $('.display-settings-save').on('click', function() {
                FormsEdit.saveDisplaySetting();
            });


            // preview
            $('.preview-popup').on('click', function() {
                FormsEdit.preview();
            });

            // save
            $('.builder-save-button').on('click', function() {
                var button = $(this);

                addButtonMask(button);
                FormsEdit.getFormsBuilder().save(function() {
                    removeButtonMask(button);
                });
            });
        })
    </script>
</body>
</html>
