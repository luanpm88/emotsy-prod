<script>
    // Rss Widget
    class RssWidget extends Widget {
        getHtmlId() {
            return "RssWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="_1content widget-text">
                    <div class="panel__body woo-panel__body" title="{{ trans('messages.automation.woo_item') }}">
                        <div class="image-drag">
                            <div ng-bind-html="::getModuleIcon(module)" class="ng-binding product-list-widget">
                                <img builder-element style="width:50px;opacity:0.5" src="{{ url('images/rss_widget.svg') }}" width="100%" />
                            </div>
                        </div>
                        <div class="body__title">{{ trans('messages.widget.rss') }}</div>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div id="`+this.id+`"
                    class="rss-widget"
                    builder-element="RssElement"
                    builder-draggable
                    data-preview="no"
                    data-config='{{ json_encode(\Acelle\Model\Template::defaultRssConfig()) }}'
                ></div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }

        getElement() {
            return currentEditor.getIframeContent().find('#' + this.id);
        }

        drop() {
            var element = currentEditor.elementFactory(this.getElement());

            currentEditor.select(element);
            currentEditor.handleSelect();

            element.render();
        }
    }
</script>