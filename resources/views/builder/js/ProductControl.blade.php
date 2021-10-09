<script>
    // Cart items Control
    class ProductControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="ProductListControl">
                    <div class="control-[ID]">
                        <div class="widget-section d-flex align-items-center pr-3">
                            <div class="label mr-auto">{{ trans('messages.woo_item.product') }}</div>
                            <div class="d-flex align-items-center">
                                <a href="javascript:;" class="btn btn-default btn-widget-preview mr-1">
                                    <span class="material-icons-outlined">visibility</span>
                                </a>
                                <a href="javascript:;" class="btn btn-default btn-widget-unpreview mr-1">
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
            
            var url = '{{ action('ProductController@widgetProductOptions') }}';

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
                $(thisControl.selector).find('.btn-widget-preview').hide();
                $(thisControl.selector).find('.btn-widget-unpreview').hide();                    
                return;
            }

            if (value && value == 'yes') {
                $(thisControl.selector).find('.btn-widget-unpreview').show();
                $(thisControl.selector).find('.btn-widget-preview').hide();
            } else {
                $(thisControl.selector).find('.btn-widget-preview').show();
                $(thisControl.selector).find('.btn-widget-unpreview').hide();
            }
        }

        update() {
            var thisControl = this;

            if ($(thisControl.selector).find('.product_id').val()) {
                var url = '{{ action('ProductController@widgetProduct') }}';
                $.ajax({
                    method: "GET",
                    url: url,
                    data: {
                        product_id: $(thisControl.selector).find('.product_id').val()
                    }
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
            $(thisControl.selector).find('.btn-widget-preview').on('click', function(e) {
                thisControl.callback.preview(function() {
                    thisControl.tooglePreview('yes');
                });                        
            });

            // unpreview
            $(thisControl.selector).find('.btn-widget-unpreview').on('click', function(e) {
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
</script>