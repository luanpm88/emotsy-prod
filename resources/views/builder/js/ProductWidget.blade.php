<script>
    // Product Cart Item Widget
    class ProductWidget extends Widget {
        getHtmlId() {
            return "ProductWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="_1content widget-text">
                    <div class="panel__body woo-panel__body" title="{{ trans('messages.automation.woo_item') }}">
                        <div class="image-drag">
                            <div ng-bind-html="::getModuleIcon(module)" class="ng-binding product-list-widget single">
                                <img builder-element src="{{ url('images/product-icon.svg') }}" width="100%" />
                            </div>
                        </div>
                        <div class="body__title">{{ trans('messages.automation.woo_item') }}</div>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div preview="no" builder-element="ProductElement" builder-draggable class="product-widget">
                    <div class="container py-3 position-relative">
                        <div class="product-placeholder text-center" style="display:none">
                            <img src="{{ url('images/product-image-placeholder.svg') }}" width="100%" />
                            <a style="" class="mt-4 btnx btnx-primary">
                                {{ trans('messages.automation.choose_a_product') }}
                            </a>
                        </div>
                        <div class="product-content">
                            <span class="woo-button product-preview-but" style="display:none">Preview</span>
                            <span class="woo-button product-unpreview-but" style="display:none">Close preview</span>
                            <div class="edit-container">
                                <div class="d-flex">
                                    <div class="">
                                        <a href="*|PRODUCT_URL|*" class="product-link">
                                            <img builder-element="ProductImgElement" class="mr-4" src="{{ url('images/product-image-placeholder.svg') }}" width="200px" />
                                        </a>
                                    </div>
                                    
                                    <div builder-element="TextElement" class="" style="width:100%">
                                        <div builder-element="TextElement">
                                            <h3 class="font-weight-normal">
                                                <a style="color:#333;" class="d-block product-link" href="*|PRODUCT_URL|*">
                                                    <span class="product-name">*|PRODUCT_NAME|*</span>
                                                </a>
                                            </h3>
                                            <p class=" product-description">*|PRODUCT_DESCRIPTION|*</p>
                                            <h4><strong class="product-price">*|PRODUCT_PRICE|*</strong></h4>
                                        </div>
                                        <div>
                                            <a style="background-color: #9b5c8f;
            border-color: #9b5c8f;" builder-element builder-inline-edit href="*|PRODUCT_URL|*" class="mt-4 btn btn-primary text-white product-view-button product-link">
                                                {{ trans('messages.automation.buy_now') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-container" style="display:none"></div>
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }
</script>