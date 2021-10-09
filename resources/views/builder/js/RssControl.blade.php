<script>
    // Rss Control
    class RssControl extends Control {
        renderHtml() {
            var thisControl = this;
            var html = `
                <div id="RssControl">
                    <div class="control-[ID]">
                        <div class="widget-row px-3 py-2 d-flex align-items-center">
                            <div class="label mr-auto">{{ trans('messages.rss.url') }}</div>
                            <div class="place-value">
                                <input type="text" value="" class="form-control rss-url">
                            </div>
                        </div>
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
            `;
            thisControl.selector = ".control-" + thisControl.id;

            html = html.replace("[ID]", thisControl.id);

            var div = $('<DIV>').html(html);
            
            return div.html();
        }

        afterRender() {
            var thisControl = this;

            // set value
            $(thisControl.selector).find('.rss-url').val(thisControl.value.url);
            $(thisControl.selector).find('.rss-count').val(thisControl.value.count);

            // set url
            $(thisControl.selector).find('.rss-url').on('change', function(e) {
                thisControl.callback.setOptions({
                    url: $(this).val()
                });
            });

            // set count
            $(thisControl.selector).find('.rss-count').on('change', function(e) {
                thisControl.callback.setOptions({
                    count: $(this).val()
                });
            });
        }
    }
</script>