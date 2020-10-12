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

    <style>
        .woo-cart-items-widget {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 40px;
        }
        .woo-cart-items-widget img {
            padding: 3px;
            width: 42px;
            height: 51px;
        }
    </style>

    <script>
        // Cart items Control
        class CartItemsControl extends Control {
            renderHtml() {
                var thisControl = this;
                var html = `
                    <div id="CartItemsControl">
                        <div class="control-[ID]">
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.cart_items.number_of_items') }}</div>
                                <select class="max-items form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.cart_items.display_option') }}</div>
                                <select class="display-option form-control">
                                    <option value="1">{{ trans('messages.cart_items.display_option.1_column') }}</option>
                                    <option value="2">{{ trans('messages.cart_items.display_option.2_column') }}</option>
                                    <option value="3">{{ trans('messages.cart_items.display_option.3_column') }}</option>
                                    <option value="4">{{ trans('messages.cart_items.display_option.4_column') }}</option>
                                </select>
                            </div>
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.cart_items.sort_by') }}</div>
                                <select class="sort-by form-control">
                                    <option value="price_az">{{ trans('messages.cart_items.sort_by.price_az') }}</option>
                                    <option value="price_za">{{ trans('messages.cart_items.sort_by.price_za') }}</option>
                                    <option value="date_added_asc">{{ trans('messages.cart_items.sort_by.date_added_asc') }}</option>
                                    <option value="date_added_desc">{{ trans('messages.cart_items.sort_by.date_added_desc') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                thisControl.selector = ".control-" + thisControl.id;

                html = html.replace("[ID]", thisControl.id);
                html = html.replace("[TITLE]", thisControl.title);

                var div = $('<DIV>').html(html);
                
                return div.html();
            }

            getValues() {
                var thisControl = this;
                
                $(thisControl.selector).find('.max-items').val(thisControl.value.max_items);
                $(thisControl.selector).find('.display-option').val(thisControl.value.display);
                $(thisControl.selector).find('.sort-by').val(thisControl.value.sort_by);
            }

            afterRender() {
                var thisControl = this;

                // set max items
                $(thisControl.selector).find('.max-items').on('change', function(e) {
                    thisControl.callback.setMaxItems($(this).val());
                });

                // set display
                $(thisControl.selector).find('.display-option').on('change', function(e) {
                    thisControl.callback.setDisplay($(this).val());
                });

                // set sort by
                $(thisControl.selector).find('.sort-by').on('change', function(e) {
                    thisControl.callback.setSortBy($(this).val());
                });

                // get values
                thisControl.getValues();
            }
        }
        
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
                    new CartItemsControl('{{ trans('messages.cart_items.number_of_items') }}', {
                            max_items: element.obj.attr('data-max-items'),
                            display: element.obj.attr('data-display'),
                            sort_by: element.obj.attr('data-sort-by')
                        } , {
                            setMaxItems: function(max_items) {
                                element.obj.attr('data-max-items', max_items);
                                element.select();
                            },
                            setDisplay: function(display) {
                                element.obj.attr('data-display', display);
                                element.select();
                            },
                            setSortBy: function(sort_by) {
                                element.obj.attr('data-sort-by', sort_by);
                                element.select();
                            }
                        }
                    ),
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

        // Woo Cart Items Widget
        class CartItemsWidget extends Widget {
            getHtmlId() {
                return "CartItemsWidget";
            }

            init() {
                // default button html
                this.setButtonHtml(`
                    <div class="_1content widget-text">
                        <div class="panel__body" title="{{ trans('messages.automation.cart_items') }}">
                            <div class="image-drag">
                                <div ng-bind-html="::getModuleIcon(module)" class="ng-binding woo-cart-items-widget">
                                    <img builder-element src="{{ url('images/woo-products.svg') }}" width="100%" />
                                </div>
                            </div>
                            <div class="body__title">{{ trans('messages.automation.cart_items') }}</div>
                        </div>
                    </div>
                `);

                // default content html
                this.setContentHtml(`
                    <div data-items-number="3" builder-element="CartItemsElement" data-max-items="6" data-display="3" data-sort-by="date_added_desc" builder-draggable class="py-3 px-3">
                        <div class="row py-3">
                            <div class="col-md-4">
                                <div class="">
                                    <div class="mb-3">
                                        <a href="{PRODUCT_URL}" class="mr-4"><img builder-element src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <p builder-element class="font-weight-normal product-name mb-1">
                                            <a style="color: #333;" href="{PRODUCT_URL}" class="mr-4">{PRODUCT_NAME}</a>
                                        </p>
                                        <p builder-element class=" product-description">{PRODUCT_DESCRIPTION}</p>
                                        <p builder-element><strong>{PRODUCT_PRICE}</strong></p>
                                        <a builder-element builder-inline-edit href="{PRODUCT_URL}" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.view_more') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="">
                                    <div class="mb-3">
                                        <a href="{PRODUCT_URL}" class="mr-4"><img builder-element src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <h5 builder-element class="font-weight-normal product-name">
                                            <a style="color: #333;" href="{PRODUCT_URL}" class="mr-4">{PRODUCT_NAME}</a>
                                        </h5>
                                        <p builder-element class=" product-description">{PRODUCT_DESCRIPTION}</p>
                                        <p builder-element><strong>{PRODUCT_PRICE}</strong></p>
                                        <a style="background-color: #9b5c8f;
    border-color: #9b5c8f;" builder-element builder-inline-edit href="{PRODUCT_URL}" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.view_more') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="">
                                    <div class="mb-3">
                                        <a href="{PRODUCT_URL}" class="mr-4"><img builder-element src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <h5 builder-element class="font-weight-normal product-name">
                                            <a style="color: #333;" href="{PRODUCT_URL}" class="mr-4">{PRODUCT_NAME}</a>
                                        </h5>
                                        <p builder-element class=" product-description">{PRODUCT_DESCRIPTION}</p>
                                        <p builder-element><strong class="product-price">{PRODUCT_PRICE}</strong></p>
                                        <a style="background-color: #9b5c8f;
    border-color: #9b5c8f;" builder-element builder-inline-edit href="{PRODUCT_URL}" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.view_more') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // default dragging html
                this.setDraggingHtml(this.getButtonHtml());
            }
        }

        // Cart items Control
        class CartItemControl extends Control {
            renderHtml() {
                var thisControl = this;
                var html = `
                    <div id="CartItemsControl">
                        <div class="control-[ID]">
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.cart_item.product') }}</div>
                                <select class="product_id form-control">
                                    <option value="">Select product</option>
                                </select>
                            </div>
                        </div>
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.cart_items.view_option') }}</div>
                            <select class="items-number form-control">
                                <option value="full">{{ trans('messages.cart_items.view_option.full') }}</option>
                                <option value="compact">{{ trans('messages.cart_items.view_option.compact') }}</option>
                                <option value="no_image">{{ trans('messages.cart_items.view_option.no_image') }}</option>
                            </select>
                        </div>
                    </div>
                `;
                thisControl.selector = ".control-" + thisControl.id;

                html = html.replace("[ID]", thisControl.id);
                html = html.replace("[TITLE]", thisControl.title);

                var div = $('<DIV>').html(html);
                
                return div.html();
            }

            getValues() {
                var thisControl = this;
                
                var url = 'http://wordpress.wsl/wp-json/acelle/connect';

                // select2 control
                $(thisControl.selector).find('.product_id').select2({
                    placeholder: 'Select product',
                    allowClear: true,
                    width: 'resolve',
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,

                        data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                        },
                        processResults: function (data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            params.page = params.page || 1;

                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    minimumInputLength: 0,
                });
            }

            update() {
                var thisControl = this;

                if ($(thisControl.selector).find('.product_id').val()) {
                    var url = 'http://wordpress.wsl/wp-json/acelle/connect?product_id=' + $(thisControl.selector).find('.product_id').val();
                    $.ajax({
                        method: "GET",
                        url: url
                    })
                    .done(function( data ) {
                        thisControl.callback.updateImage(data.image);
                        thisControl.callback.updateName(data.name);
                        thisControl.callback.updateDescription(data.description);
                        thisControl.callback.updateLink(data.link);
                        thisControl.callback.updatePrice(data.price);
                    });
                } else {
                    thisControl.callback.updateImage('{{ url('images/cart_item.svg') }}');
                    thisControl.callback.updateName('{PRODUCT_NAME}');
                    thisControl.callback.updateDescription('{PRODUCT_DESCRIPTION}');
                    thisControl.callback.updateLink('{PRODUCT_URL}');
                    thisControl.callback.updatePrice('{PRODUCT_PRICE}');
                }
            }

            afterRender() {
                var thisControl = this;

                // copy url
                $(thisControl.selector).find('.product_id').on('change', function(e) {
                    thisControl.update();
                });

                // get values
                thisControl.getValues();
            }
        }

        // cart items element
        class CartItemElement extends SuperElement  {
            name() {
                return getI18n('block');
            }
            icon() {
                return 'fal fa-font';
            }

            getControls() {
                var element = this;

                return [
                    new CartItemControl('{{ trans('messages.cart_item') }}', {
                        image: element.obj.find('img').attr('src'),
                    }, {
                        updateImage: function(src) {
                            element.obj.find('img').attr('src', src);

                            element.select();
                        },
                        updateName: function(name) {
                            element.obj.find('.product-name').html(name);

                            element.select();
                        },
                        updateDescription: function(description) {
                            element.obj.find('.product-description').html(description);

                            element.select();
                        },
                        updateLink: function(link) {
                            element.obj.find('.product-link').attr('href', link);

                            element.select();
                        },
                        updatePrice: function(price) {
                            element.obj.find('.product-price').html(price);

                            element.select();
                        }
                    }),
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
                        <div class="panel__body" title="{{ trans('messages.automation.cart_item') }}">
                            <div class="image-drag">
                                <div ng-bind-html="::getModuleIcon(module)" class="ng-binding woo-cart-items-widget">
                                    <img builder-element src="{{ url('images/woo-product.svg') }}" width="100%" />
                                </div>
                            </div>
                            <div class="body__title">{{ trans('messages.automation.cart_item') }}</div>
                        </div>
                    </div>
                `);

                // default content html
                this.setContentHtml(`
                    <div data-items-number="3" builder-element="CartItemElement" builder-draggable class="py-3 px-3">
                        <div class="d-flex">
                            <div class="">
                                <a href="{PRODUCT_URL}">
                                    <img builder-element  class="mr-4" src="{{ url('images/cart_item.svg') }}" width="200px" />
                                </a>
                            </div>
                            <div class="">
                                <div class="">
                                    <h3 class="font-weight-normal">
                                        <a style="color:#333;" builder-element builder-inline-edit class="d-block product-name" href="{PRODUCT_URL}">
                                            *|PRODUCT_NAME|*
                                        </a>
                                    </h3>
                                    <p builder-element class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                    <h4 builder-element><strong class="product-price">*|PRODUCT_PRICE|*</strong></h4>
                                    <a style="background-color: #9b5c8f;
    border-color: #9b5c8f;" builder-element builder-inline-edit href="{PRODUCT_URL}" class="mt-4 btn btn-primary text-white">
                                        {{ trans('messages.automation.view_more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // default dragging html
                this.setDraggingHtml(this.getButtonHtml());
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
                    @if ($automation->getTrigger()->getOption('type') == 'woo-abandoned-cart')
                        this.addContentWidget(new CartItemsWidget(), 0, 'WooCommerce');
                        this.addContentWidget(new CartItemWidget(), 0, 'WooCommerce');
                    @endif
                }
            });
          
            editor.init();
        });
    </script>

    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery.min.js') }}"></script>
    <link href="{{ URL::asset('assets2/lib/select2-4.0.12/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ URL::asset('assets2/lib/select2-4.0.12/js/select2.min.js') }}"></script>
  </head>
  <body>
  </body>
</html>
