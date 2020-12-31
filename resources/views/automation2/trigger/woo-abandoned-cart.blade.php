<div class="mb-20">
    <input type="hidden" name="options[type]" value="woo-abandoned-cart" />

    <p class="small">{!! trans('messages.automation.trigger.woo-abandoned-cart.download_plugin', [
        'link' => route('download', ['name' => 'acelle-sync.zip']),
    ]) !!}</p>
    
    @php
        $trigger = $automation->getTrigger();
        $shopinfo = $trigger->wooGetShopInfo();
    @endphp
    @if (isset($shopinfo['name']) && !request()->options)
        <div class="wp-shop-info mb-4 mt-4">
            <div class="d-flex align-items-center">
                <div class="logo-col mr-3">
                    <img src="{{ $shopinfo['logo'] ? $shopinfo['logo'] : url('images/Wordpress-Logo.svg') }}" class="shop_logo" />
                </div>
                <div class="logo-info" style="line-height: 19px">
                    <p class="mb-1">{!! trans('messages.automation.connected_to_store', [
                        'store' => $shopinfo['name']
                    ]) !!}</p>
                    <div class="small">
                        <a target="_blank" href="{{ $shopinfo['url'] }}">{{ trans('messages.automation.visit_store') }}</a> |
                        <a target="_blank" href="{{ $shopinfo['admin_url'] }}">{{ trans('messages.automation.site_administrator') }}</a>
                    </div>
                </div>
                <div class="ml-auto">
                    <a href="javascript:;" class="btn btn-secondary change-connect-url text-nowrap" onclick="$('.edit-connect-url').removeClass('hide');$('.wp-shop-info').hide();$('.trigger-save-change').show()">
                        {{ trans('messages.automation.connect_url.change_store') }}
                    </a>
                </div>
            </div>
            <div class="bottom-info">
                <div class="d-flex align-items-center">
                    <h5 class="mb-1">{{ $shopinfo['name'] }}</h5>
                    <span class="badge badge-success ml-2">{{ trans('messages.active') }}</span>
                </div>
                <p>{{ trans('messages.automation.associated_with_store.wording', [
                    'store' => $shopinfo['name']
                ]) }}</p>
                <div class="d-flex align-items-center store-states justify-content-space-between mt-4">
                    <div class="d-flex align-items-center">
                        <span class="store-icon">
                            <span class="material-icons-outlined">
                                loyalty
                            </span>
                        </span>
                        <div class="">
                            <label class="mb-0 display-1">{{ $shopinfo['products_count'] }}</label>
                            <div class="text-muted small">{{ trans('messages.woo.products') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center store-states">
                        <span class="store-icon">
                            <span class="material-icons-outlined">
                                assignment
                            </span>
                        </span>
                        <div class="">
                            <label class="mb-0 display-1">{{ $shopinfo['orders_count'] }}</label>
                            <div class="text-muted small">{{ trans('messages.woo.orders') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center store-states">
                        <span class="store-icon">
                            <span class="material-icons">
                                insights
                            </span>
                        </span>
                        <div class="">
                            <label class="mb-0 display-1">{!! $shopinfo['total_sales'] !!}</label>
                            <div class="text-muted small">{{ trans('messages.woo.total_sales') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .trigger-save-change {
                display: none;
            }
        </style>
    @endif

    <div class="edit-connect-url {{ isset($shopinfo['name']) && !request()->options ? 'hide' : '' }}">
        @include('helpers.form_control', [
            'type' => 'text',
            'class' => '',
            'label' => '',
            'name' => 'options[connect_url]',
            'value' => $trigger->getOption('connect_url'),
            'help_class' => 'trigger',
            'rules' => [],
        ])
    </div>
</div>