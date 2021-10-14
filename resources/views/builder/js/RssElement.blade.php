<script>
    // rss element
    class RssElement extends SuperElement  {
        name() {
            var element = this; 
            return getI18n('block');
        }

        getOptions() {
            var _this = this;

            return {
                preview: this.obj.attr('data-preview'),
                config: JSON.parse(Base64.decode(this.obj.attr('data-config'))),
                getSelectedItem: function() {
                    return _this.selectedItem;
                }                
            };
        }

        setOptions(options) {
            var _this = this;

            if (typeof(options.preview) != 'undefined') {
                this.obj.attr('data-preview', options.preview);
            }
            if (typeof(options.config) != 'undefined') {
                this.obj.attr('data-config', Base64.encode(JSON.stringify($.extend({}, _this.getOptions().config, options.config))));
            }

            _this.render();

            currentEditor.handleSelect();
        }

        addEvents() {
            var element = this;
            window.cElement = this;

            // preview
            element.obj.find('.ace-preview').on('click', function() {
                element.setOptions({
                    preview: 'yes'
                });
            });

            // preview
            element.obj.find('.ace-unpreview').on('mouseup', function() {
                element.setOptions({
                    preview: 'no'
                });
            });

            // hover rss items
            element.obj.find('[rss-item]').mouseenter( function() {
                var rssItem = element.rssItemFactory($(this));
                rssItem.highlight();
            } ).mouseleave( function() {
                var rssItem = element.rssItemFactory($(this));
                rssItem.removeHighlight();
            } );

            // click outside item
            element.obj.find('[rss-item]').on('click', function(e) {
                var rssItem = element.rssItemFactory($(this));
                element.selectItem(rssItem);
            } )

            // click no item
            element.obj.on('click', function(e) {
                if(!$(e.target).closest('[rss-item]').length) {
                    element.unselectItem();
                }
            } )

            // set before save event
            element.setBeforeSaveEvent();
        }

        selectItem(rssItem) {
            console.log('unselect');
            this.unselectItem();

            //
            console.log('select');
            this.selectedItem = rssItem;            
            this.selectedItem.select();

            // reload controls
            currentEditor.handleSelect();
        }

        unselectItem() {
            if (typeof(this.selectedItem) == 'undefined' || this.selectedItem == null) {
                return;
            }

            this.selectedItem.unselect();
            this.selectedItem = null;

            currentEditor.handleSelect();
        }

        rssItemFactory(item) {
            return new RssItem(this, item);
        }

        setBeforeSaveEvent() {
            var element = this;
            var id = element.obj.attr('id');

            // editor before save events.
            if (typeof(window.rssEvents) == 'undefined') {
                window.rssEvents = [];
            }

            if (typeof(window.rssEvents[id]) == 'undefined') {
                window.rssEvents[id] = true;

                // before save
                currentEditor.addBeforeSaveEvent(function() {
                    element.setOptions({
                        preview: 'no'
                    });
                });
            }
        }

        render() {
            var _this = this;

            if (_this.getOptions().preview == 'no') {
                _this.loadPlaceholder();
            } else if (_this.getOptions().preview == 'yes') {
                _this.loadRss();
            }
        }

        setContent(content) {
            var button;
            if (this.getOptions().preview == 'no') {
                button = `
                    <button class="btn btn-secondary ace-rss-button ace-preview shadown-sm" style="display:none">{{ trans('messages.rss.preview') }}</button>
                `;
            } else {
                button = `
                    <button class="btn btn-secondary ace-rss-button ace-unpreview shadown-sm" style="display:none">{{ trans('messages.rss.unpreview') }}</button>
                `;
            }

            var html =  `
                <div style="position:relative">
                    `+content+`
                    `+button+`
                </div>
            `;

            this.obj.html(html);

            this.addEvents();
        }

        loadPlaceholder() {
            var _this = this;

            _this.setContent(`
                <div class="container py-3" style="position:relative">
                    <div class="rss-placeholder">
                        <img src="{{ url('images/rss-placeholder.svg') }}" width="100%" />
                    </div>
                </div>
            `);

            currentEditor.select(_this);
            currentEditor.handleSelect();
        }

        loadRss() {
            var _this = this;
            var options = _this.getOptions();

            if (options.config.url == '') {
                alert('{{ trans('messages.rss.url_required') }}');
                return;
            }

            // 
            _this.addLoadingEffect();
            $.ajax({
                url: '{!! action('TemplateController@parseRss') !!}',
                method: 'GET',
                data: options,
                statusCode: {
                    // validate error
                    400: function (res) {
                        console.log('Something went wrong!');
                    }
                },
                success: function (response) {
                    _this.setContent(response);
                    _this.removeLoadingEffect();

                    currentEditor.select(_this);
                }
            });
        }

        addLoadingEffect() {
            var _this = this;

            this.removeLoadingEffect();

            _this.obj.addClass('ace-loading');
            _this.obj.append(`<div class="ace-loader"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background-image: none; display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <circle cx="30" cy="50" fill="#774023" r="20">
                <animate attributeName="cx" repeatCount="indefinite" dur="1s" keyTimes="0;0.5;1" values="30;70;30" begin="-0.5s"/>
                </circle>
                <circle cx="70" cy="50" fill="#d88c51" r="20">
                <animate attributeName="cx" repeatCount="indefinite" dur="1s" keyTimes="0;0.5;1" values="30;70;30" begin="0s"/>
                </circle>
                <circle cx="30" cy="50" fill="#774023" r="20">
                <animate attributeName="cx" repeatCount="indefinite" dur="1s" keyTimes="0;0.5;1" values="30;70;30" begin="-0.5s"/>
                <animate attributeName="fill-opacity" values="0;0;1;1" calcMode="discrete" keyTimes="0;0.499;0.5;1" dur="1s" repeatCount="indefinite"/>
                </circle>
                <!-- [ldio] generated by https://loading.io/ --></svg></div>
            `);
        }

        removeLoadingEffect() {
            var _this = this;
            _this.obj.removeClass('ace-loading');
            _this.obj.find('.ace-loader').remove();
        }

        updateTemplate(key, options) {
            var _this = this;

            var templates = _this.getOptions().config.templates;
            templates[key] = $.extend({}, templates[key], options);

            _this.setOptions({
                config: {
                    templates: templates
                }
            });
        }

        getControls() {
            var element = this;

            window.testE = this;

            var id = element.obj.attr('id');
            if (typeof(window.rssEvents1) == 'undefined') {
                window.rssEvents1 = [];
            }

            if (typeof(window.rssEvents1[id]) == 'undefined') {
                window.rssEvents1[id] = true;

                element.render();
            }

            return [
                new RssControl(getI18n('font_family'), element.getOptions(), {
                    setOptions: function(options) {
                        element.setOptions(options);
                    },
                    saveItemShow: function(key, show) {
                        element.updateTemplate(key, {
                            show: show
                        });
                    },
                    selectItemByClass: function(name) {
                        if (element.obj.find('[rss-item="'+name+'"]').length) {
                            var item = element.rssItemFactory(element.obj.find('[rss-item="'+name+'"]'));
                            element.selectItem(item);
                        }
                    },
                    unselectItem: function() {
                        element.unselectItem();
                    },
                    saveItemTemplate: function(key, content) {
                        element.updateTemplate(key, {
                            template: content
                        });
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