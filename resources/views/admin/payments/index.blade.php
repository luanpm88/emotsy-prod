@extends('layouts.backend')

@section('title', trans('messages.payment_gateways'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-credit-card2"></i> {{ trans('messages.payment_gateways') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <form action="{{ action('Admin\SettingController@payment') }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-8">
                <div class="sub-section">
                    <h2>{{ trans('messages.payment.default_payment_gateway') }}</h2>
                    <p>{{ trans('messages.payment_gateways.wording') }}</p>
                    <div class="d-flex align-items-center">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.offline_payment') }}</h4>
                            <p>{{ trans('messages.payment.offline_payment.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div  style="width: 200px">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'offline_payment',
                                    'value' => config('cashier.offline_payment'),
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['offline_payment' => 'required'],
                                    'options' => [
                                        ['value' => 'none', 'text' => trans('messages.none')],
                                        ['value' => 'direct', 'text' => trans('messages.payments.direct')],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.card_payment') }}</h4>
                            <p>{{ trans('messages.payment.card_payment.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div  style="width: 200px">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'card_payment',
                                    'value' => config('cashier.card_payment'),
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['card_payment' => 'required'],
                                    'options' => [
                                        ['value' => 'none', 'text' => trans('messages.none')],
                                        ['value' => 'stripe', 'text' => trans('messages.payments.stripe')],
                                        ['value' => 'braintree', 'text' => trans('messages.payments.braintree')],
                                        ['value' => 'razorpay', 'text' => trans('messages.payments.razorpay')],
                                        ['value' => 'paystack', 'text' => trans('messages.payments.paystack')],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                    @if (config('cashier.card_payment') != 'none')
                        @php
                            $error = false;
                            try {
                                \Acelle\Cashier\Cashier::getPaymentGateway(config('cashier.card_payment'))->validate();
                            } catch (\Exception $ex) {
                                @endphp
                                    <div class="">
                                        @include('elements._notification', [
                                            'level' => 'error',
                                            'title' => trans('messages.payment.can_not_connect', ['name' => trans('messages.payments.' . config('cashier.card_payment'))]),
                                            'message' => trans('messages.payment.can_not_connect.desc', [
                                                'error' => $ex->getMessage(),
                                                'link' => action('Admin\PaymentController@edit', config('cashier.card_payment')),
                                            ]),
                                        ])
                                    </div>
                                @php
                            }                            
                        @endphp
                    @endif
                    <div class="d-flex align-items-center">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.digital_coin') }}</h4>
                            <p>{{ trans('messages.payment.digital_coin.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div  style="width: 200px">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'digital_coin_payment',
                                    'value' => config('cashier.digital_coin_payment'),
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['digital_coin_payment' => 'required'],
                                    'options' => [
                                        ['value' => 'none', 'text' => trans('messages.none')],
                                        ['value' => 'coinpayments', 'text' => trans('messages.payments.coinpayments')],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                    @if (config('cashier.digital_coin_payment') != 'none')
                        @php
                            $error = false;
                            try {
                                \Acelle\Cashier\Cashier::getPaymentGateway(config('cashier.digital_coin_payment'))->validate();
                            } catch (\Exception $ex) {
                                @endphp
                                    <div class="">
                                        @include('elements._notification', [
                                            'level' => 'error',
                                            'title' => trans('messages.payment.can_not_connect', ['name' => trans('messages.payments.' . config('cashier.digital_coin_payment'))]),
                                            'message' => trans('messages.payment.can_not_connect.desc', [
                                                'error' => $ex->getMessage(),
                                                'link' => action('Admin\PaymentController@edit', config('cashier.digital_coin_payment')),
                                            ]),
                                        ])
                                    </div>
                                @php
                            }                            
                        @endphp
                    @endif
                    <div class="d-flex align-items-center">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.paypal_payment') }}</h4>
                            <p>{{ trans('messages.payment.paypal_payment.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div  style="width: 200px">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'paypal_payment',
                                    'value' => config('cashier.paypal_payment'),
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['paypal_payment' => 'required'],
                                    'options' => [
                                        ['value' => 'none', 'text' => trans('messages.none')],
                                        ['value' => 'paypal', 'text' => trans('messages.payments.paypal')],
                                        ['value' => 'paypal_subscription', 'text' => trans('messages.payments.paypal_subscription')],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                    @if (config('cashier.paypal_payment') != 'none')
                        @php
                            $error = false;
                            try {
                                \Acelle\Cashier\Cashier::getPaymentGateway(config('cashier.paypal_payment'))->validate();
                            } catch (\Exception $ex) {
                                @endphp
                                    <div class="">
                                        @include('elements._notification', [
                                            'level' => 'error',
                                            'title' => trans('messages.payment.can_not_connect', ['name' => trans('messages.payments.' . config('cashier.paypal_payment'))]),
                                            'message' => trans('messages.payment.can_not_connect.desc', [
                                                'error' => $ex->getMessage(),
                                                'link' => action('Admin\PaymentController@edit', config('cashier.paypal_payment')),
                                            ]),
                                        ])
                                    </div>
                                @php
                            }                            
                        @endphp
                    @endif
                </div>
                <div class="sub-section mt-40">
                    <h2>{{ trans('messages.payment.settings') }}</h2>
                        <div class="row">
                            <div class="col-md-8">
                                @include('helpers.form_control', [
                                    'type' => 'number',
                                    'name' => 'end_period_last_days',
                                    'value' => config('cashier.end_period_last_days'),
                                    'label' => trans('messages.system.end_period_last_days'),
                                    'help_class' => 'setting',
                                    'rules' => ['end_period_last_days' => 'required'],
                                ])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group checkbox-right-switch">
                                    @include('helpers.form_control', [
                                        'type' => 'checkbox',
                                        'name' => 'renew_free_plan',
                                        'value' => config('cashier.renew_free_plan'),
                                        'label' => trans('messages.system.renew_free_plan'),
                                        'help_class' => 'setting',
                                        'options' => ['no', 'yes'],
                                        'rules' => ['renew_free_plan' => 'required'],
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">  
                                    @include('helpers.form_control', [
                                        'type' => 'number',
                                        'name' => 'recurring_charge_before_days',
                                        'value' => config('cashier.recurring_charge_before_days'),
                                        'label' => trans('messages.system.recurring_charge_before_days'),
                                        'help_class' => 'setting',
                                        'options' => ['no', 'yes'],
                                        'rules' => ['recurring_charge_before_days' => 'required'],
                                    ])
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-mc_primary mt-4">
                            {{ trans('messages.save') }}
                        </button>
                </div>
            </div>
        </div>
    </form>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <div class="sub-section">
                <h2>{{ trans('messages.payment.all_available_gateways') }}</h2>
                <p>{{ trans('messages.payment.all_available_gateways.wording') }}</p>
                <div class="mc-list-setting mt-20">
                    @foreach ($gateways as $name => $gateway)
                        <div class="list-setting bg-{{ $gateway['name'] }} current">
                            <div class="list-setting-main">
                                <div class="title">
                                    <label>{{ trans('messages.payments.' . $name) }}</label>
                                    @if (\Acelle\Model\Setting::get('system.payment_gateway') == $name)
                                        <span class="label label-info bg-primary ml-4">{{ trans('messages.payment.primary') }}</span>
                                    @endif
                                </div>
                                <p>{{ trans('messages.payments.' . $name . '.list_intro') }}</p>
                            </div>
                            <div class="list-setting-footer">
                                <a class="btn btn-mc_default ml-5" href="{{ action('Admin\PaymentController@edit', $name) }}">
                                    {{ trans('messages.payment.setting') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    

@endsection
