<script>
    // Rss Control
    class RssControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="RssControl">
                    <div class="control-[ID]">
                        <div class="px-3">
                            <h4>{{ trans('messages.widget.rss') }}</h4>
                            <p>{{ trans('messages.widget.rss.desc') }}</p>
                        </div>
                        <div class="start-block">
                            <div class="px-3 py-2 d-flex align-items-center border-bottom">
                                <button class="click-start btn btn-primary" style="width:100%">
                                    {{ trans('messages.widget.rss.click_start') }}
                                </button>
                            </div>
                        </div>
                        <div class="setting-block">
                            <div class="widget-row px-3 py-2 d-flex align-items-center ">
                                <div class="place-value" style="width:100%">
                                    <input type="text" value="" class="form-control rss-url">
                                </div>
                                <button class="click-load btn btn-secondary ml-2">
                                    {{ trans('messages.widget.rss.load_url') }}
                                </button>
                                <button class="click-change btn btn-secondary ml-2">
                                    {{ trans('messages.widget.rss.change_url') }}
                                </button>
                            </div>
                            <div class="display-count">
                                <div class="widget-row px-3 py-2 d-flex align-items-center">
                                    <div class="label mr-auto">{{ trans('messages.rss.posts') }}</div>
                                    <div class="place-value">
                                        <select class="form-control rss-count">
                                            @for ($i = 1; $i < 50; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor        
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            `;
            thisControl.selector = ".control-" + thisControl.id;

            html = html.replace("[ID]", thisControl.id);

            var div = $('<DIV>').html(html);
            
            return div.html();
        }

        showStart() {
            var thisControl = this;

            $(thisControl.selector).find('.start-block').show();
            $(thisControl.selector).find('.setting-block').hide();
        }

        showChangeUrl() {
            var thisControl = this;

            $(thisControl.selector).find('.start-block').hide();
            $(thisControl.selector).find('.setting-block').show();

            // 
            $(thisControl.selector).find('.rss-url').prop('readonly', false);
            $(thisControl.selector).find('.click-load').show();
            $(thisControl.selector).find('.click-change').hide();
            $(thisControl.selector).find('.display-count').hide();
        }

        loadUrl() {
            var thisControl = this;

            if (thisControl.getUrl() == '') {
                alert('{{ trans('messages.rss.url_required') }}');
                return;
            }

            currentEditor.mask();

            $.ajax({
                url: '{!! action('TemplateController@parseRss') !!}',
                method: 'GET',
                data: {
                    url: thisControl.getUrl()
                }
            }).fail(function(e) {
                alert(e.responseText);
                currentEditor.unmask();
            }).done(function() {
                thisControl.callback.setOptions({
                    url: thisControl.getUrl(),
                    preview: 'yes',
                });
                currentEditor.unmask();
            });
        }

        showLoadUrl() {
            var thisControl = this;

            console.log('show load');

            $(thisControl.selector).find('.start-block').hide();
            $(thisControl.selector).find('.setting-block').show();

            // 
            $(thisControl.selector).find('.rss-url').prop('readonly', true);
            $(thisControl.selector).find('.click-load').hide();
            $(thisControl.selector).find('.click-change').show();
            $(thisControl.selector).find('.display-count').show();
        }

        getUrl() {
            var thisControl = this;

            return $(thisControl.selector).find('.rss-url').val();
        }

        afterRender() {
            var thisControl = this;

            // show blocks
            if (thisControl.value.url == '') {
                thisControl.showStart();
            } else {
                thisControl.showLoadUrl();
            }


            // set value
            $(thisControl.selector).find('.rss-url').val(thisControl.value.url);
            $(thisControl.selector).find('.rss-count').val(thisControl.value.count);

            // set count
            $(thisControl.selector).find('.rss-count').on('change', function(e) {
                thisControl.callback.setOptions({
                    count: $(this).val()
                });
            });
            
            // click start
            $(thisControl.selector).find('.click-start').on('click', function(e) {
                thisControl.showChangeUrl();
            });

            // click change
            $(thisControl.selector).find('.click-change').on('click', function(e) {
                thisControl.showChangeUrl();
            });

            // load url
            $(thisControl.selector).find('.click-load').on('click', function(e) {
                thisControl.loadUrl();
            });
        }
    }
</script>