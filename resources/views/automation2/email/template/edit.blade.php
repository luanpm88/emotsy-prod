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
        .woo-cart-items-widget.single img {
            padding: 3px;
            width: 58px;
            height: 81px;
        }
        .woo-cart-items-widget img {
            padding: 3px;
            width: 65px;
            height: 81px;
        }
        .woo-panel__body {
            background-color: #fff8fe!important;
        }
        .btn-preview, .btn-unpreview {
            padding: 3px 10px;
            height: 28px;
        }
        .btn-preview span, .btn-unpreview span {
            font-size: 20px;
        }
    </style>

    <script>
        // IMG element
        class WooImgElement extends SuperElement {
            name() {
                return getI18n('image');
            }
            icon() {
                return 'fal fa-image';
            }

            getControls() {
                var element = this;

                var link = element.obj.parent().length && element.obj.parent().is("a") ? element.obj.parent().attr('href') : '';

                return [
                    new ImageControl(getI18n('align'), { readonly: true, width: element.obj.css('width'), src: element.obj.attr('src'), alt: element.obj.attr('alt'), auto_width: element.obj.attr('width') == 'auto'}, function(options) {
                        element.obj.css('width', options.range);
                        element.obj.parent().css('text-align', options.align);
                        element.obj.css('margin', 'auto');
                        element.obj.attr('src', options.src);
                        element.obj.addClass('image-after-change');
                        setTimeout(function() {
                            currentEditor.select(element);
                            if (typeof(options.src) != 'undefined') {
                                currentEditor.handleSelect();
                            }
                        }, 100);
                        
                        if (options.auto_width) {
                            element.obj.css('width', 'auto');
                        }
                    }),
                    new ImageSizeControl(getI18n('image_size'), {
                        width: element.obj.width(),
                        height: element.obj.height()
                    }, function(options) {
                        element.obj.width(options.width);
                        element.obj.height(options.height);
                    }),

                    //            
                    new ImageLinkControl(getI18n('image_link'), {
                        readonly: true,
                        url: link
                    }, function(options) {
                        if (element.obj.parent().is("a")) {
                            element.obj.parent().attr('href', options.url);
                        } else {
                            element.obj.wrap( "<a href='" + options.url + "'></a>" );
                        }
                    }),
                    new BlockOptionControl(getI18n('block_options'), { padding: element.obj.css('padding'), top: element.obj.css('padding-top'), bottom: element.obj.css('padding-bottom'), right: element.obj.css('padding-right'), left: element.obj.css('padding-left') }, function(options) {
                        // apply ngược lại cho element.obj
                        element.obj.css('padding', options.padding);
                        element.obj.css('padding-top', options.top);
                        element.obj.css('padding-bottom', options.bottom);
                        element.obj.css('padding-right', options.right);
                        element.obj.css('padding-left', options.left);
                        setTimeout(function() {
                            currentEditor.select(element);
                        }, 100);
                    }),
                ];
            }
        }

        // Cart items Control
        class WooItemsControl extends Control {
            renderHtml() {
                var thisControl = this;
                var html = `
                    <div id="WooItemsControl">
                        <div class="control-[ID]">
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.woo_items.number_of_items') }}</div>
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
                                <div class="label mr-auto">{{ trans('messages.woo_items.display_option') }}</div>
                                <select class="display-option form-control">
                                    <option value="1">{{ trans('messages.woo_items.display_option.1_column') }}</option>
                                    <option value="2">{{ trans('messages.woo_items.display_option.2_column') }}</option>
                                    <option value="3">{{ trans('messages.woo_items.display_option.3_column') }}</option>
                                    <option value="4">{{ trans('messages.woo_items.display_option.4_column') }}</option>
                                </select>
                            </div>
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.woo_items.sort_by') }}</div>
                                <select class="sort-by form-control">
                                    <option value="price_az">{{ trans('messages.woo_items.sort_by.price_az') }}</option>
                                    <option value="price_za">{{ trans('messages.woo_items.sort_by.price_za') }}</option>
                                    <option value="date_added_asc">{{ trans('messages.woo_items.sort_by.date_added_asc') }}</option>
                                    <option value="date_added_desc">{{ trans('messages.woo_items.sort_by.date_added_desc') }}</option>
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
        class WooItemsElement extends SuperElement  {
            name() {
                return getI18n('block');
            }
            icon() {
                return 'fal fa-font';
            }

            preview() {
                var element = this;

                element.obj.find('.woo-button').addClass('loading');

                var url = '{{ $automation->getTrigger()->getOption('connect_url') }}?action=list&max=' + element.obj.attr('data-max-items') + '&sort_by=' + element.obj.attr('data-sort-by');
                $.ajax({
                    method: "GET",
                    url: url
                })
                .done(function( data ) {
                    element.obj.attr('preview', 'yes');
                    
                    element.obj.find('.woo-items').html('');
                    data.forEach( function(item) {
                        var cols = (12/element.obj.attr('data-display'));
                        var midCols = cols > 6 ? 12 : 6;
                        var row = `
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-`+midCols+` col-md-` +(12/element.obj.attr('data-display'))+ `">
                                <div class="">
                                    <div class="img-col mb-3">
                                        <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <a style="width:100%" href="`+item.link+`" class="mr-4"><img width="100%" src="`+(item.image ? item.image : '{{ url('images/cart_item.svg') }}')+`" style="max-height:200px;max-width:100%;" /></a>
                                        </div>
                                    </div>
                                    <div class="">
                                        <p class="font-weight-normal product-name mb-1">
                                            <a style="color: #333;" href="`+item.link+`" class="mr-4">`+item.name+`</a>
                                        </p>
                                        <p class=" product-description">`+item.description+`</p>
                                        <p><strong>`+item.price+`</strong></p>
                                        <a href="`+item.link+`" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.buy_now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;

                        element.obj.find('.woo-items').append(row);
                    });

                    if (element.obj.attr('data-display') == '1') {
                        element.obj.find('.img-col').attr('style', 'float: left;margin-right: 20px;width: 150px;margin-bottom:0!important');
                    } else {
                        element.obj.find('.img-col').attr('style', '');
                    }

                    if (editor.selected != null) {
                        editor.selected.select();
                    }

                    element.obj.find('.woo-button').removeClass('loading');
                });         
            }

            unpreview() {
                var element = this;

                element.obj.find('.woo-button').addClass('loading');

                element.display(element.obj.attr('data-display'));

                element.obj.attr('preview', 'no');

                if (editor.selected != null) {
                    editor.selected.select();
                }

                element.obj.find('.woo-button').removeClass('loading');
            }

            display(display) {
                var element = this;

                element.obj.find('.woo-items').html('');
                var preItems = element.obj.attr('data-max-items') > display ? display : element.obj.attr('data-max-items');
                for(var i=0; i<preItems;i++) {
                    element.obj.find('.woo-items').append(`
                        <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-4">
                            <div class="">
                                <div class="img-col mb-3">
                                    <a href="*|PRODUCT_URL|*" class="mr-4"><img src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                </div>
                                <div class="">
                                    <p class="font-weight-normal product-name mb-1">
                                        <a style="color: #333;" href="*|PRODUCT_URL|*" class="mr-4">*|PRODUCT_NAME|*</a>
                                    </p>
                                    <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                    <p><strong>*|PRODUCT_PRICE|*</strong></p>
                                    <a href="*|PRODUCT_URL|*" style="background-color: #9b5c8f;
border-color: #9b5c8f;" class="btn btn-primary text-white">
                                        {{ trans('messages.automation.buy_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    `);
                }

                element.obj.find('.woo-col-item').removeClass(function (index, className) {
                    return (className.match (/(^|\s)col-md-\S+/g) || []).join(' ');
                });

                element.obj.find('.woo-col-item').removeClass(function (index, className) {
                    return (className.match (/(^|\s)col-sm-\S+/g) || []).join(' ');
                });

                element.obj.find('.woo-col-item').addClass('col-md-' + (12/display));

                element.obj.find('.woo-col-item').addClass('col-sm-' + ((12/display) > 6 ? 12 : 6));

                if (display == '1') {
                    element.obj.find('.img-col').attr('style', 'float: left;margin-right: 20px;width: 150px;margin-bottom:0!important');
                } else {
                    element.obj.find('.img-col').attr('style', '');
                }

                if (element.obj.attr('data-max-items') > display) {
                    element.obj.find('.woo-items').addClass('more');
                } else {
                    element.obj.find('.woo-items').removeClass('more');
                }
            }

            getControls() {
                var element = this;

                return [
                    new WooItemsControl('{{ trans('messages.woo_items.number_of_items') }}', {
                            max_items: element.obj.attr('data-max-items'),
                            display: element.obj.attr('data-display'),
                            sort_by: element.obj.attr('data-sort-by'),
                            preview: element.obj.attr('preview'),
                        } , {
                            setMaxItems: function(max_items) {
                                element.obj.attr('data-max-items', max_items);                                

                                if (element.obj.attr('preview') == 'yes') {
                                    element.preview();
                                } else {
                                    element.display(element.obj.attr('data-display'));
                                }

                                element.select();
                            },
                            setDisplay: function(display) {
                                element.obj.attr('data-display', display);     

                                if (element.obj.attr('preview') == 'yes') {
                                    element.preview();
                                } else {
                                    element.display(element.obj.attr('data-display'));
                                }

                                element.select();
                            },
                            setSortBy: function(sort_by) {
                                element.obj.attr('data-sort-by', sort_by);

                                if (element.obj.attr('preview') == 'yes') {
                                    element.preview();
                                } else {
                                    element.display(element.obj.attr('data-display'));
                                }

                                element.select();
                            },
                            preview: function() {
                                element.preview();
                            },
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
        class WooItemsWidget extends Widget {
            getHtmlId() {
                return "WooItemsWidget";
            }

            init() {
                // default button html
                this.setButtonHtml(`
                    <div class="_1content widget-text">
                        <div class="panel__body woo-panel__body" title="{{ trans('messages.automation.woo_item') }}">
                            <div class="image-drag">
                                <div ng-bind-html="::getModuleIcon(module)" class="ng-binding woo-cart-items-widget">
                                    <img builder-element src="{{ url('images/wooproductlist.svg') }}" width="100%" />
                                </div>
                            </div>
                            <div class="body__title">{{ trans('messages.automation.woo_items') }}</div>
                        </div>
                    </div>
                `);

                // default content html
                this.setContentHtml(`
                    <div data-items-number="4" builder-element="WooItemsElement" data-max-items="4" data-display="4" data-sort-by="date_added_desc" builder-draggable class="py-3 px-3 woo-cart-items-widget">
                        <span class="woo-button woo-preview-but" style="display:none">Preview</span>
                        <span class="woo-button woo-unpreview-but" style="display:none">Close preview</span>
                        <div class="row py-3 woo-items">
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
                                <div class="">
                                    <div class="img-col mb-3">
                                        <a href="*|PRODUCT_URL|*" class="mr-4"><img src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <p class="font-weight-normal product-name mb-1">
                                            <a style="color: #333;" href="*|PRODUCT_URL|*" class="mr-4">*|PRODUCT_NAME|*</a>
                                        </p>
                                        <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                        <p><strong>*|PRODUCT_PRICE|*</strong></p>
                                        <a href="*|PRODUCT_URL|*" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.buy_now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
                                <div class="">
                                    <div class="img-col mb-3">
                                        <a href="*|PRODUCT_URL|*" class="mr-4"><img src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <p class="font-weight-normal product-name mb-1">
                                            <a style="color: #333;" href="*|PRODUCT_URL|*" class="mr-4">*|PRODUCT_NAME|*</a>
                                        </p>
                                        <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                        <p><strong>*|PRODUCT_PRICE|*</strong></p>
                                        <a href="*|PRODUCT_URL|*" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.buy_now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
                                <div class="">
                                    <div class="img-col mb-3">
                                        <a href="*|PRODUCT_URL|*" class="mr-4"><img src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <p class="font-weight-normal product-name mb-1">
                                            <a style="color: #333;" href="*|PRODUCT_URL|*" class="mr-4">*|PRODUCT_NAME|*</a>
                                        </p>
                                        <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                        <p><strong>*|PRODUCT_PRICE|*</strong></p>
                                        <a href="*|PRODUCT_URL|*" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.buy_now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="woo-col-item mb-4 mt-4 col-12 col-sm-6 col-md-3">
                                <div class="">
                                    <div class="img-col mb-3">
                                        <a href="*|PRODUCT_URL|*" class="mr-4"><img src="{{ url('images/cart_item.svg') }}" width="100%" /></a>
                                    </div>
                                    <div class="">
                                        <p class="font-weight-normal product-name mb-1">
                                            <a style="color: #333;" href="*|PRODUCT_URL|*" class="mr-4">*|PRODUCT_NAME|*</a>
                                        </p>
                                        <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                        <p><strong>*|PRODUCT_PRICE|*</strong></p>
                                        <a href="*|PRODUCT_URL|*" style="background-color: #9b5c8f;
    border-color: #9b5c8f;" class="btn btn-primary text-white">
                                            {{ trans('messages.automation.buy_now') }}
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
        class WooItemControl extends Control {
            renderHtml() {
                var thisControl = this;
                var html = `
                    <div id="WooItemsControl">
                        <div class="control-[ID]">
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.woo_item.product') }}</div>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:;" class="btn btn-default btn-preview mr-1">
                                        <span class="material-icons-outlined">visibility</span>
                                    </a>
                                    <a href="javascript:;" class="btn btn-default btn-unpreview mr-1">
                                        <span class="material-icons-outlined">visibility_off</span>
                                    </a>
                                    <select class="product_id form-control">
                                        <option value="">Select product</option>
                                    </select>                                    
                                </div>
                            </div>
                            <div class="widget-section d-flex align-items-center pr-3">
                                <div class="label mr-auto">{{ trans('messages.woo_items.view_option') }}</div>
                                <select class="display-option form-control">
                                    <option value="full">{{ trans('messages.woo_items.view_option.full') }}</option>
                                    <option value="compact">{{ trans('messages.woo_items.view_option.compact') }}</option>
                                    <option value="no_image">{{ trans('messages.woo_items.view_option.no_image') }}</option>
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
                
                var url = '{{ $automation->getTrigger()->getOption('connect_url') }}';

                // set product id
                if (thisControl.value.id) {
                    $(thisControl.selector).find('.product_id').append('<option value="'+thisControl.value.id+'">'+thisControl.value.name+'</option>');
                    $(thisControl.selector).find('.product_id').val(thisControl.value.id);
                }

                // 
                thisControl.tooglePreview(thisControl.value.preview);

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

                // display
                var display = 'full';
                if (thisControl.value.display) {
                    display = thisControl.value.display;
                }
                $(thisControl.selector).find('.display-option').val(display);
            }

            tooglePreview(value) {
                var thisControl = this;

                if (!$(thisControl.selector).find('.product_id').val()) {
                    $(thisControl.selector).find('.btn-preview').hide();
                    $(thisControl.selector).find('.btn-unpreview').hide();                    
                    return;
                }

                if (value && value == 'yes') {
                    $(thisControl.selector).find('.btn-unpreview').show();
                    $(thisControl.selector).find('.btn-preview').hide();
                } else {
                    $(thisControl.selector).find('.btn-preview').show();
                    $(thisControl.selector).find('.btn-unpreview').hide();
                }
            }

            update() {
                var thisControl = this;

                if ($(thisControl.selector).find('.product_id').val()) {
                    var url = '{{ $automation->getTrigger()->getOption('connect_url') }}?product_id=' + $(thisControl.selector).find('.product_id').val();
                    $.ajax({
                        method: "GET",
                        url: url
                    })
                    .done(function( data ) {
                        thisControl.callback.updateId(data.id);                        
                        thisControl.callback.updateName(data.name);
                        {{-- thisControl.callback.updateImage(data.image);
                        thisControl.callback.updateDescription(data.description);
                        thisControl.callback.updateLink(data.link);
                        thisControl.callback.updatePrice(data.price); --}}

                        thisControl.callback.preview(function() {
                            thisControl.tooglePreview('yes');
                        });  
                    });                    
                } else {
                    thisControl.callback.updateId('');                    
                    thisControl.callback.updateName('');

                    thisControl.callback.unpreview();
                }

                thisControl.tooglePreview(thisControl.value.preview);
            }

            afterRender() {
                var thisControl = this;

                // copy url
                $(thisControl.selector).find('.product_id').on('change', function(e) {
                    thisControl.update();
                });
                
                // preview
                $(thisControl.selector).find('.btn-preview').on('click', function(e) {
                    thisControl.callback.preview(function() {
                        thisControl.tooglePreview('yes');
                    });                        
                });

                // unpreview
                $(thisControl.selector).find('.btn-unpreview').on('click', function(e) {
                    thisControl.callback.unpreview(function() {
                        thisControl.tooglePreview('no');
                    });
                });
                
                // display
                $(thisControl.selector).find('.display-option').on('change', function(e) {
                    thisControl.callback.display($(this).val());
                });

                // get values
                thisControl.getValues();
            }
        }

        // cart items element
        class WooItemElement extends SuperElement  {
            name() {
                return getI18n('block');
            }
            icon() {
                return 'fal fa-font';
            }

            preview() {
                var element = this;                

                element.obj.find('.woo-button').addClass('loading');

                if(element.obj.attr('product-id') == null || element.obj.attr('product-id') == '') {
                    Swal.fire({
                        title: '{{ trans('messages.woo_item.please_select_product') }}',
                        text: '',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ trans('messages.ok') }}'
                    }).then((result) => {
                        element.obj.find('.woo-button').removeClass('loading');
                    });
                    return;
                }

                var url = '{{ $automation->getTrigger()->getOption('connect_url') }}?product_id=' + element.obj.attr('product-id');
                $.ajax({
                    method: "GET",
                    url: url
                })
                .done(function( data ) {
                    element.obj.attr('preview', 'yes');

                    if (data.image) {
                        element.obj.find('img').attr('src', data.image);
                    } else {
                        element.obj.find('img').attr('src', '{{ url('images/cart_item.svg') }}');
                    }
                    element.obj.find('.product-name').html(data.name);
                    element.obj.find('.product-description').html(data.description);
                    element.obj.find('.product-link').attr('href', data.link);
                    element.obj.find('.product-price').html(data.price);

                    if (editor.selected != null) {
                        editor.selected.select();
                    }

                    element.obj.find('.woo-button').removeClass('loading');
                });             
            }

            unpreview() {
                var element = this;

                element.obj.find('.woo-button').addClass('loading');

                element.obj.attr('preview', 'no');

                element.obj.find('img').attr('src', '{{ url('images/cart_item.svg') }}');
                element.obj.find('.product-name').html('*|PRODUCT_NAME|*');
                element.obj.find('.product-description').html('*|PRODUCT_DESCRIPTION|*');
                element.obj.find('.product-link').attr('href', '*|PRODUCT_URL|*');
                element.obj.find('.product-price').html('*|PRODUCT_PRICE|*');
                if (editor.selected != null) {
                    editor.selected.select();
                }

                element.obj.find('.woo-button').removeClass('loading');
            }

            getControls() {
                var element = this;

                return [
                    new WooItemControl('{{ trans('messages.woo_item') }}', {
                        id: element.obj.attr('product-id'),
                        name: element.obj.attr('product-name'),
                        preview: element.obj.attr('preview'),
                        display: element.obj.attr('display'),
                    }, {
                        updateId: function(id) {
                            element.obj.attr('product-id', id);

                            element.select();
                        },
                        updateName: function(name) {
                            element.obj.attr('product-name', name);

                            element.select();
                        },
                        preview: function(callback) {
                            element.preview();                            
                            if (callback) {
                                callback();
                            }
                        },
                        unpreview: function(callback) {
                            element.unpreview();
                            if (callback) {
                                callback();
                            }
                        },
                        display: function(display) {
                            element.obj.attr('display', display);

                            if (display == 'full') {
                                element.obj.find('img').show();
                                element.obj.find('img').css('width', '200px');
                                element.obj.find('.product-view-button').show();
                            }

                            if (display == 'compact') {
                                element.obj.find('img').show();
                                element.obj.find('img').css('width', '100px');
                                element.obj.find('.product-view-button').hide();
                            }

                            if (display == 'no_image') {
                                element.obj.find('img').hide();
                                element.obj.find('.product-view-button').hide();
                            }
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
        class WooItemWidget extends Widget {
            getHtmlId() {
                return "WooItemWidget";
            }

            init() {
                // default button html
                this.setButtonHtml(`
                    <div class="_1content widget-text">
                        <div class="panel__body woo-panel__body" title="{{ trans('messages.automation.woo_item') }}">
                            <div class="image-drag">
                                <div ng-bind-html="::getModuleIcon(module)" class="ng-binding woo-cart-items-widget single">
                                    <img builder-element src="{{ url('images/wooproduct.svg') }}" width="100%" />
                                </div>
                            </div>
                            <div class="body__title">{{ trans('messages.automation.woo_item') }}</div>
                        </div>
                    </div>
                `);

                // default content html
                this.setContentHtml(`
                    <div preview="no" builder-element="WooItemElement" builder-draggable class="py-3 px-3 woo-cart-item-widget">
                        <span class="woo-button woo-preview-but" style="display:none">Preview</span>
                        <span class="woo-button woo-unpreview-but" style="display:none">Close preview</span>
                        <div class="d-flex">
                            <div class="">
                                <a href="*|PRODUCT_URL|*" class="product-link">
                                    <img builder-element="WooImgElement" class="mr-4" src="{{ url('images/cart_item.svg') }}" width="200px" />
                                </a>
                            </div>
                            <div class="">
                                <div class="">
                                    <h3 class="font-weight-normal">
                                        <a style="color:#333;" builder-element builder-inline-edit class="d-block product-link" href="{PRODUCT_URL}">
                                            <span class="product-name">*|PRODUCT_NAME|*</span>
                                        </a>
                                    </h3>
                                    <p builder-element class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                    <h4 builder-element><strong class="product-price">*|PRODUCT_PRICE|*</strong></h4>
                                    <a style="background-color: #9b5c8f;
    border-color: #9b5c8f;" builder-element builder-inline-edit href="*|PRODUCT_URL|*" class="mt-4 btn btn-primary text-white product-view-button product-link">
                                        {{ trans('messages.automation.buy_now') }}
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

    <style>
        .logo-img {
            width: 120px!important;
            margin-right: 15px;
        }
    </style>

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
                    '{{ url('/images/backgrounds/images17.png') }}',
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
                    var thisEditor = this;

                    @if ($automation->getTrigger()->getOption('type') == 'woo-abandoned-cart')
                        this.addContentWidget(new WooItemsWidget(), 0, 'WooCommerce');
                        this.addContentWidget(new WooItemWidget(), 0, 'WooCommerce');
                    @endif

                    // add custom css
                    this.addCustomCss('{{ url('/css/builder_woo.css') }}');

                    // click preview button
                    $("#builder_iframe").contents().find("body").on('click', '.woo-preview-but', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var element = thisEditor.elementFactory($(this).parent());
                        element.preview();
                        {{-- thisEditor.select(element); --}}
                        thisEditor.handleSelect();
                        return false;
                    })

                    $("#builder_iframe").contents().find("body").on('click', '.woo-unpreview-but', function(e) {
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
        });
    </script>

    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery.min.js') }}"></script>
    <link href="{{ URL::asset('assets2/lib/select2-4.0.12/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ URL::asset('assets2/lib/select2-4.0.12/js/select2.min.js') }}"></script>
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
