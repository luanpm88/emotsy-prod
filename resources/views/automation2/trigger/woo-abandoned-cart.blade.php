<div class="mb-20">
    <input type="hidden" name="options[type]" value="woo-abandoned-cart" />
    
    @php
        $trigger = $automation->getTrigger();
        $shopinfo = $trigger->wooGetShopInfo();
    @endphp
    @if (isset($shopinfo['name']) && !request()->options)
        <div class="wp-shop-info align-items-center mb-4">
            <div class="logo-col mr-3">
                <img src="{{ $shopinfo['logo'] ? $shopinfo['logo'] : url('images/Wordpress-Logo.svg') }}" class="shop_logo" />
            </div>
            <div class="logo-info">
                <div class="">
                    <label class="mb-1"><strong>{{ trans('messages.automation.shop_name') }}</strong>: {{ $shopinfo['name'] }}</label>
                </div>
                <div class="">
                    <label class="mb-0"><strong>{{ trans('messages.automation.shop_url') }}</strong>: <a href="{{ $shopinfo['url'] }}" target="_blank">{{ $shopinfo['url'] }}</a></label>
                </div>
            </div>
            <div class="ml-auto">
                <a href="javascript:;" class="btn btn-secondary change-connect-url" onclick="$('.edit-connect-url').removeClass('hide');$('.wp-shop-info').hide();$('.trigger-save-change').show()">
                    {{ trans('messages.automation.connect_url.change') }}
                </a>
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