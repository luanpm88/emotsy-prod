<!doctype html>
<html>
  <head>
    <title>{{ trans('messages.edit_template') }} - {{ $template->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('layouts.core._favicon')
    
    <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

    @if ($template->theme)
        @include('builder.themes.' . $template->theme)
    @endif

    <style>
        .logo-img {
            width: 120px!important;
            margin-right: 15px;
        }
        .tox .tox-dialog__body-content .urlfill-dropdown {
            position: absolute;
            background: #fff;
            width: 100%;
            padding: 0;
            box-shadow: 2px 2px 3px rgba(0,0,0,0.3);
            border: solid 1px #bbb;
        }
        .tox .tox-dialog__body-content .urlfill-dropdown li {
            list-style: none;
            padding: 5px 12px;
            border-bottom: solid 1px #bbb;
            cursor: pointer;
        }
        .tox .tox-dialog__body-content .urlfill-dropdown li:last-child {
            border-bottom: none;
        }
        .tox .tox-dialog__body-content .urlfill-dropdown li.current {
            background: #efefef;
        }
        .tox .tox-dialog__body-content .urlfill-dropdown li:hover {
            background: #eee;
        }
        .tox-dialog__popups, .tox-dialog__popups * {
            display: none !important;
        }
        .tox .tox-dialog__body-content .urlautofill-input {
            position: absolute;
            left: 1px;
            top: 1px;
            height: 34px;
            padding-left: 6px;
            width: 98%;
            background: #fff;
        }
    </style>

    @include('helpers._builder_product_widgets')
    
    <script>
        // auto fill url input
        class UrlAutoFill {
            constructor(data) {
                var _this = this;
                _this.keyup = null;
                _this.data = data;
                _this.values = [];                

                if (!_this.keyup) {
                    _this.keyup = $(document).on('focus', '.tox-form__group:first-child input', function(event) {
                        if (!$('.urlautofill-input').length) {
                            var input = $('<input type="text" class="urlautofill-input">');
                            $(this).after(input);
                            input.focus();
                            input.val($(this).val());

                            _this.input = input;
                        }
                    });

                    $(document).on('input', '.tox-form__group:first-child input', function(event) {
                        console.log('input');
                    });

                    $(document).on('keyup change', '.urlautofill-input', function(event) {
                        if(event.keyCode !== 13 && event.keyCode !== 38 && event.keyCode !== 40 ) {
                            _this.updateValues();
                            _this.showDropdown();
                        }
                        $('.tox-form__group:first-child input').val(_this.input.val());
                    })

                    $(document).on('keydown', '.urlautofill-input', function(event) {
                        if(event.keyCode == 38) {
                            _this.moveUp();
                        }
                        if(event.keyCode == 40) {
                            _this.moveDown();
                        }
                        if(event.keyCode == 13) {
                            if (_this.current) {
                                _this.select(_this.current);
                            }
                        }
                    });

                    _this.keyword = function() {
                        return $('.urlautofill-input').val();
                    }

                    $(document).on('mousedown', '.urlfill-dropdown li', function() {
                        _this.select($(this));
                    })

                    $(document).on('focus click', '.urlautofill-input', function(event) {
                        _this.input = $(this);
                        _this.updateValues();
                        setTimeout(function() {
                            _this.showDropdown();
                            _this.input.val($('.tox-form__group:first-child input').val());

                            _this.input.css('opacity', '1');
                        }, 100);
                    });

                    $(document).on('focusout', '.urlautofill-input', function(event) {
                        setTimeout(function() {
                            _this.hideDropdown();
                            _this.input.val($('.tox-form__group:first-child input').val());
                        }, 100);

                        _this.input.css('opacity', '0');
                    });
                    
                    $(document).on('mousedown', '.tox-browse-url', function(event) {
                        $('.tox-form__group:first-child input').focus();
                    });

                    $(document).on('change', '.tox-form__group:first-child input', function(event) {
                        setTimeout(function() {
                            _this.input.val($('.tox-form__group:first-child input').val());
                        }, 100);
                    });
                }                    
            }

            moveUp() {
                var _this = this;
                if (_this.dropdown) {
                    if (!_this.current || !_this.current.prev().length) {
                        _this.setCurrent(_this.dropdown.find('li').last());
                    } else {
                        _this.setCurrent(_this.current.prev());
                    }
                }
            }

            moveDown() {
                var _this = this;
                if (_this.dropdown) {
                    if (!_this.current || !_this.current.next().length) {
                        _this.setCurrent(_this.dropdown.find('li').first());
                    } else {
                        _this.setCurrent(_this.current.next());
                    }
                }
            }

            setCurrent(li) {
                var _this = this;
                _this.current = li;

                _this.dropdown.find('li').removeClass('current');
                _this.current.addClass('current');
            }

            updateValues() {
                var _this = this;
                _this.values = [];

                var matches = [];
                _this.data.forEach(function(item) {
                    if (_this.keyword() != '' && item.value.toLowerCase().indexOf(_this.keyword().toLowerCase()) === 0) {
                        matches.push(item.value);
                        _this.values.push(item);
                    }
                });

                if (_this.keyword() == '') {
                    _this.data.forEach(function(item) {
                        if (!matches.includes(item.value)) {
                            _this.values.push(item);
                        }
                    });
                }
            }

            showDropdown() {
                var _this = this;
                
                // remove current dropdown
                _this.hideDropdown();

                if (_this.values.length) {
                    _this.dropdown = $('<ul class="urlfill-dropdown">');

                    _this.values.forEach(function(item) {
                        _this.dropdown.append($('<li data="'+item.text+'">').html(item.value));
                    });

                    _this.input.after(_this.dropdown);
                    // select first
                    _this.setCurrent(_this.dropdown.find('li').first());
                }
            }

            hideDropdown() {
                var _this = this;
                
                // remove current dropdown
                if (_this.dropdown) {
                    _this.dropdown.remove();
                    _this.dropdown = null;
                }
            }

            select(item) {
                var _this = this;
                var value = item.html();

                console.log(item);

                _this.input.val(value);
                _this.input.focusout();

                if (_this.input.closest('.tox-form__group').next().find('input').val() == '') {
                    _this.input.closest('.tox-form__group').next().find('input').val(item.attr('data'));
                }

                $('.tox-form__group:first-child input').val(_this.input.val());
            }
        }        

        $(function () {
            var urlFill = new UrlAutoFill([
                {value: '{UNSUBSCRIBE_URL}', text: '{{ trans('messages.editor.unsubscribe_text') }}'},
                {value: '{UPDATE_PROFILE_URL}', text: '{{ trans('messages.editor.update_profile_text') }}'},
                {value: '{WEB_VIEW_URL}', text: '{{ trans('messages.editor.click_view_web_version') }}'}
            ]);
        });
        
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var editor;
        
        var templates = {!! json_encode($templates) !!};
        
        $( document ).ready(function() {
            editor = new Editor({
                strict: true,
                showHelp: false,
                showInlineToolbar: false,
                lang: {!! json_encode(language()->getBuilderLang()) !!},
                url: '{{ action('TemplateController@builderEditContent', $template->uid) }}',
                backCallback: function() {
                    if (parent.$('.full-iframe-popup').length) {
                        parent.$('.full-iframe-popup').hide();
                        parent.$('body').removeClass('overflow-hidden');
                    }
                    
                    if (parent.$('.listing-form').length) {
                        parent.TemplatesIndex.getList().load();
                    } else {
                        window.location = '{{ action('TemplateController@index') }}';
                    }    
                },
                uploadAssetUrl: '{{ action('TemplateController@uploadTemplateAssets', $template->uid) }}',
                uploadAssetMethod: 'POST',
                saveUrl: '{{ action('TemplateController@builderEdit', $template->uid) }}',
                saveMethod: 'POST',
                tags: {!! json_encode(Acelle\Model\Template::builderTags((isset($list) ? $list : null))) !!},
                root: '{{ URL::asset('builder') }}/',
                templates: templates,
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
                customInlineEdit: function(container) {
                    var thisEditor = this;

                    var tinyconfig = {
                      skin: 'oxide-dark',
                      inline: true,
                      menubar: false,
                      force_br_newlines : false,
                      force_p_newlines : false,
                      forced_root_block : '',
                      inline_boundaries: false,
                      relative_urls: false,
                      convert_urls: false,
                      typeahead_urls: false,
                      remove_script_host : false,
                      valid_elements : '*[*],meta[*]',
                      valid_children: '+h1[div],+h2[div],+h3[div],+h4[div],+h5[div],+h6[div],+a[div]',
                      plugins: 'image link lists autolink',
                      //toolbar: 'undo redo | bold italic underline | fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent',
                      toolbar: [
                          // 'undo redo | bold italic underline | fontselect fontsizeselect | link | menuDateButton',
                          // 'forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent'
                      ],
                      external_filemanager_path:'{{ url('/') }}'.replace('/index.php','')+"/filemanager2/",
                      filemanager_title:"Responsive Filemanager" ,
                      external_plugins: { "filemanager" : '{{ url('/') }}'.replace('/index.php','')+"/filemanager2/plugin.min.js"},
                      setup: function (editor) {
                      
                          /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                          /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                          editor.ui.registry.addMenuButton('menuDateButton', {
                            text: getI18n('editor.insert_tag'),
                            fetch: function (callback) {
                              var items = [];

                              thisEditor.tags.forEach(function(tag) {
                                  if ( tag.type == 'label') {
                                      items.push({
                                          type: 'menuitem',
                                          text: tag.tag.replace("{", "").replace("}", ""),
                                          onAction: function (_) {
                                              if (tag.text) {
                                                  editor.insertContent(tag.text);
                                              } else {
                                                  editor.insertContent(tag.tag);
                                              }                                            
                                          }
                                      });
                                  }
                              });
                              
                              callback(items);
                            }
                          });
                      }
                  };

                  var unsupported_types = 'td, table, img, body';
                  if (!container.is(unsupported_types) && (container.is('[builder-inline-edit]') || !editor.strict)) {
                      container.addClass('builder-class-tinymce');
                      tinyconfig.selector = '.builder-class-tinymce';
                      editor.tinymce = $("#builder_iframe")[0].contentWindow.tinymce.init(tinyconfig);

                      container.removeClass('builder-class-tinymce');
                  }

                  // fixing td tinymce
                  if (container.is('td')) {
                      if (!container.find('.tinymce-td-fix').length) {
                          var span = $('<div class="tinymce-td-fix builder-class-tinymce">');
                          span.html(container.html());

                          container.html('');
                          container.append(span);

                          span.click();
                      }
                  }
                },
                loaded: function() {
                    var thisEditor = this;

                    if (typeof(WidgetManager) !== 'undefined') {
                        var widgets = WidgetManager.init();

                        widgets.forEach(function(widget) {
                            thisEditor.addContentWidget(widget, 0, 'Template Content');
                        });
                    }

                    // // product widgets
                    // this.addContentWidget(new ProductListWidget(), 0, 'ProductCommerce');
                    // this.addContentWidget(new ProductWidget(), 0, 'ProductCommerce');
                    // add custom css
                    this.addCustomCss('{{ url('/core/css/builder_product.css') }}');
                    // click preview button
                    $("#builder_iframe").contents().find("body").on('click', '.product-preview-but', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var element = thisEditor.elementFactory($(this).parent());
                        element.preview();
                        {{-- thisEditor.select(element); --}}
                        thisEditor.handleSelect();
                        return false;
                    })

                    $("#builder_iframe").contents().find("body").on('click', '.product-unpreview-but', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var element = thisEditor.elementFactory($(this).parent());
                        element.unpreview();
                        {{-- thisEditor.select(element); --}}
                        thisEditor.handleSelect();
                        return false;
                    })
                }
            });
          
            editor.init();

            //
            $(document).on('click', '.filemanager-ok', function(e) {alert('Please click on the thumbnail to select the corresponding image');})
            $(document).on('click', '.filemanager-cancel', function(e) {$('.PopUpCloseButton').click();})
        });
    </script>
  </head>
  <body>
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
        <div style="text-align: center;
            height: 100vh;
            vertical-align: middle;
            padding: auto;
            display: flex;">
            <div style="margin:auto" class="lds-dual-ring"></div>
        </div>
  </body>
</html>
