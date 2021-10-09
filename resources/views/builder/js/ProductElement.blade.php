<script>
    // cart items element
    class ProductElement extends SuperElement  {
        name() {
            return getI18n('block');
        }
        icon() {
            return 'fal fa-font';
        }

        preview() {
            var element = this;                

            element.obj.addClass('loading');

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
                    element.obj.removeClass('loading');
                });
                return;
            }

            var url = '{{ action('ProductController@widgetProduct') }}';
            $.ajax({
                method: "GET",
                url: url,
                data: {
                    product_id: element.obj.attr('product-id')
                }
            })
            .done(function( data ) {
                element.obj.attr('preview', 'yes');

                // replace
                var html = element.obj.find('.edit-container').html();
                html = html.replace(/\*\|PRODUCT_NAME\|\*/g, data.name);
                html = html.replace(/\*\|PRODUCT_DESCRIPTION\|\*/g, data.description);
                html = html.replace(/\*\|PRODUCT_PRICE\|\*/g, data.price);
                html = html.replace(/\*\|PRODUCT_QUANTITY\|\*/g, data.quantity);
                element.obj.find('.preview-container').html(html);
                if (data.image) {
                    element.obj.find('.preview-container img').attr('src', data.image);
                }
                element.obj.find('.preview-container *').removeAttr('builder-element');

                if (editor.selected != null) {
                    editor.selected.select();
                }

                element.obj.removeClass('loading');
            });             
        }

        unpreview() {
            var element = this;

            element.obj.addClass('loading');

            element.obj.attr('preview', 'no');

            // element.obj.find('.product-link img').attr('src', '{{ url('images/cart_item.svg') }}');
            // element.obj.find('.product-name').html('*|PRODUCT_NAME|*');
            // element.obj.find('.product-description').html('*|PRODUCT_DESCRIPTION|*');
            // element.obj.find('.product-link').attr('href', '*|PRODUCT_URL|*');
            // element.obj.find('.product-price').html('*|PRODUCT_PRICE|*');
            if (editor.selected != null) {
                editor.selected.select();
            }

            element.obj.removeClass('loading');
        }

        getControls() {
            var element = this;

            return [
                new ProductControl('{{ trans('messages.woo_item') }}', {
                    id: element.obj.attr('product-id'),
                    name: element.obj.attr('product-name'),
                    preview: element.obj.attr('preview'),
                    display: element.obj.attr('display'),
                }, {
                    updateId: function(id) {
                        element.obj.attr('product-id', id);

                        if (!id) {
                            element.obj.removeAttr('product-id');
                        }

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
</script>