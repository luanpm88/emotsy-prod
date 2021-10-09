<script>
    // rss element
    class RssElement extends SuperElement  {
        name() {
            var element = this; 
            return getI18n('block');
        }

        getOptions() {
            return {
                preview: this.obj.attr('data-preview'),
                count: this.obj.attr('data-count'),
                url: this.obj.attr('data-url')
            };
        }

        setOptions(options) {
            var _this = this;

            if (typeof(options.preview) != 'undefined') {
                this.obj.attr('data-preview', options.preview);
            }
            if (typeof(options.count) != 'undefined') {
                this.obj.attr('data-count', options.count);
            }
            if (typeof(options.url) != 'undefined') {
                this.obj.attr('data-url', options.url);
            }

            _this.render();
        }

        addEvents() {
            var element = this;
            var id = element.obj.attr('id');

            // preview
            element.obj.find('.ace-preview').on('click', function() {
                element.setOptions({
                    preview: 'yes'
                });
            });

            // preview
            element.obj.find('.ace-unpreview').on('click', function() {
                element.setOptions({
                    preview: 'no'
                });
            });

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

            setTimeout(function() {
                currentEditor.select(_this);
                currentEditor.handleSelect();
            }, 100);
        }

        loadRss() {
            var _this = this;
            var options = _this.getOptions();

            if (options.url == '') {
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

        getControls() {
            var element = this;

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