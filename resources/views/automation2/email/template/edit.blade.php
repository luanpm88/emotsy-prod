<!doctype html>
<html>
  <head>
    <title>{{ trans('messages.campaign.edit_template') }} - {{ $automation->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('layouts._favicon')
    
    <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>
    @php
      // check if content from layouts
      $buildMode = strpos($email->content, 'AcelleSystemLayouts') !== false ? 'true' : 'false';
    @endphp

    <script>
        // cart items element
        class CartItemsElement extends SuperElement  {
            name() {
                return getI18n('block');
            }
            icon() {
                return 'fal fa-font';
            }

            getControls() {
                var element = this;

                return [
                    new FontFamilyControl(getI18n('font_family'), element.obj.css('font-family'), function(font_family) {
                        element.obj.css('font-family', font_family);
                        element.select();
                    }),
                    new BackgroundImageControl(getI18n('background_image'), {
                        image: element.obj.css('background-image'),
                        color: element.obj.css('background-color'),
                        repeat: element.obj.css('background-repeat'),
                        position: element.obj.css('background-position'),
                        size: element.obj.css('background-size'),
                    }, {
                        setBackgroundImage: function (image) {
                            element.obj.css('background-image', image);
                        },
                        setBackgroundColor: function (color) {
                            element.obj.css('background-color', color);
                        },
                        setBackgroundRepeat: function (repeat) {
                            element.obj.css('background-repeat', repeat);
                        },
                        setBackgroundPosition: function (position) {
                            element.obj.css('background-position', position);
                        },
                        setBackgroundSize: function (size) {
                            element.obj.css('background-size', size);
                        },
                    }),
                    new BlockOptionControl(getI18n('block_options'), { padding: element.obj.css('padding'), top: element.obj.css('padding-top'), bottom: element.obj.css('padding-bottom'), right: element.obj.css('padding-right'), left: element.obj.css('padding-left') }, function(options) {
                        element.obj.css('padding', options.padding);
                        element.obj.css('padding-top', options.top);
                        element.obj.css('padding-bottom', options.bottom);
                        element.obj.css('padding-right', options.right);
                        element.obj.css('padding-left', options.left);
                        element.select();
                    })
                ];
            }
        }

        // Woo Cart Item Widget
        class CartItemWidget extends Widget {
            getHtmlId() {
                return "CartItemWidget";
            }

            init() {
                // default button html
                this.setButtonHtml(`
                    <div class="_1content widget-text">
                        <div class="panel__body" title="{{ trans('messages.automation.cart_items') }}">
                            <div class="image-drag">
                                <div ng-bind-html="::getModuleIcon(module)" class="ng-binding">
                                    <i class="material-icons-outlined" style="font-size: 36px;display: inline-block;">money</i>
                                </div>
                            </div>
                            <div class="body__title">{{ trans('messages.automation.cart_items') }}</div>
                        </div>
                    </div>
                `);

                // default content html
                this.setContentHtml(`
                    <div builder-element="CartItemsElement" builder-draggable class="py-3 px-3">
                        <div builder-element class="d-flex mb-4">
                        <a builder-element href="" class="mr-4"><img builder-element src="{{ url('images/cart_item.svg') }}" width="200px" /></a>
                        <div class="">
                            <h3 builder-element builder-inline-edit class="font-weight-normal">{{ trans('messages.automation.item_title') }}</h3>
                            <p builder-element builder-inline-edit class="">{{ trans('messages.automation.item_description') }}</p>
                            <a builder-element builder-inline-edit class="btn btn-primary text-white">{{ trans('messages.automation.view_more') }}</a>
                        </div>
                        </div>
                    </div>
                `);

                // default dragging html
                this.setDraggingHtml(`
                    <div class="_1content widget-text">
                        <div class="panel__body" title="{{ trans('messages.automation.cart_items') }}">
                            <div class="image-drag">
                                <div ng-bind-html="::getModuleIcon(module)" class="ng-binding">
                                    <i class="material-icons-outlined" style="font-size: 36px;display: inline-block;">money</i>
                                </div>
                            </div>
                            <div class="body__title">{{ trans('messages.automation.cart_items') }}</div>
                        </div>
                    </div>
                `);
            }
        }
    </script>
    
    <script>
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var editor;
        
        var templates = {!! json_encode($templates) !!};
        
        $( document ).ready(function() {
            editor = new Editor({
                buildMode: {{ $buildMode }},
                legacyMode: true,
                showHelp: false,
                lang: {!! json_encode(language()->getBuilderLang()) !!},
                url: '{{ action('Automation2Controller@templateContent', [
                  'uid' => $automation->uid,
                  'email_uid' => $email->uid,
                ]) }}',
                backCallback: function() {
                  parent.$('.full-iframe-popup').remove();
                  popup.load();
                },
                uploadAssetUrl: '{{ action('Automation2Controller@templateAsset', [
                  'uid' => $automation->uid,
                  'email_uid' => $email->uid,
                ]) }}',
                uploadAssetMethod: 'POST',
                saveUrl: '{{ action('Automation2Controller@templateEdit', [
                  'uid' => $automation->uid,
                  'email_uid' => $email->uid,
                ]) }}',
                saveMethod: 'POST',
                tags: {!! json_encode(Acelle\Model\Template::builderTags((isset($list) ? $list : null))) !!},
                root: '{{ URL::asset('builder') }}/',
                templates: templates,
                logo: '{{ \Acelle\Model\Setting::get('site_logo_small') ? action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) : URL::asset('images/logo_light_builder.png') }}',
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
                    '{{ url('/images/backgrounds/images17.png') }}',
                ],
                customInlineEdit: function(container) {
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
                        remove_script_host : false,
                      plugins: 'image link textcolor lists autolink',
                      //toolbar: 'undo redo | bold italic underline | fontselect fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent',
                      toolbar: [
                          'undo redo | bold italic underline | fontselect fontsizeselect | link | menuDateButton',
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
                  if (!container.is(unsupported_types) && (container.is('[builder-inline-edit]') || !editor.buildMode)) {
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
                    this.addContentWidget(new CartItemWidget(), 0);
                }
            });
          
            editor.init();
        });
    </script>
  </head>
  <body>
  </body>
</html>
