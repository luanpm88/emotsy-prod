@extends('layouts.frontend')

@section('title', trans('messages.subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="active">{{ trans('messages.subscription') }}</li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.plan.review') }}</span>
        </h1>
    </div>

@endsection

@section('content')
<form action="{{ action('AccountSubscriptionController@create', ['plan_uid' => $plan->uid]) }}" method="POST" class="proceed_with_payment">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-7">
            <div class="sub-section mb-30">
                <p>{{ trans('messages.plan.review.wording') }}</p>
                
                <ul class="mc-inline-list">
                    <li>                        
                        <desc>{{ trans('messages.plan_name') }}</desc>
                        <value>{{ $plan->name }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.price') }}</desc>
                        <value>{{ Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}/{{ $plan->displayFrequencyTime() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.sending_total_quota_label') }}</desc>
                        <value>{{ $plan->displayTotalQuota() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.max_lists_label') }}</desc>
                        <value>{{ $plan->displayMaxList() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.max_subscribers_label') }}</desc>
                        <value>{{ $plan->displayMaxSubscriber() }}</value>
                    </li>
                    <li>                        
                        <desc>{{ trans('messages.max_campaigns_label') }}</desc>
                        <value>{{ $plan->displayMaxCampaign() }}</value>
                    </li>
                </ul>
            </div>
            <h2 class="mb-1">{{ trans('messages.subscription.choose_payment_method') }}</h2>
            <p>{{ trans('messages.plan.review.wording') }}</p>
            <div class="sub-section mb-30 choose-payment-methods">       
                @if (config('cashier.offline_payment') != 'none')         
                    <div class="d-flex align-items-center choose-payment">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.offline_payment') }}</h4>
                            <p>{{ trans('messages.payment.offline_payment.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div>
                                @include('helpers.form_control', [
                                    'type' => 'radio2',
                                    'name' => 'payment_method',
                                    'value' => '',
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['payment_method' => 'required'],
                                    'options' => [
                                        ['value' => config('cashier.offline_payment'), 'text' => ''],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                @endif
                @if (config('cashier.card_payment') != 'none' && isValidPaymentGateway(config('cashier.card_payment')))    
                    <div class="d-flex align-items-center choose-payment">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.card_payment') }}</h4>
                            <p>{{ trans('messages.payment.card_payment.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div>
                                @include('helpers.form_control', [
                                    'type' => 'radio2',
                                    'name' => 'payment_method',
                                    'value' => '',
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['payment_method' => 'required'],
                                    'options' => [
                                        ['value' => config('cashier.card_payment'), 'text' => ''],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                @endif
                @if (config('cashier.digital_coin_payment') != 'none' && isValidPaymentGateway(config('cashier.digital_coin_payment')))
                    <div class="d-flex align-items-center choose-payment">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.digital_coin') }}</h4>
                            <p>{{ trans('messages.payment.digital_coin.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div>
                                @include('helpers.form_control', [
                                    'type' => 'radio2',
                                    'name' => 'payment_method',
                                    'value' => '',
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['payment_method' => 'required'],
                                    'options' => [
                                        ['value' => config('cashier.digital_coin_payment'), 'text' => ''],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                @endif
                @if (config('cashier.paypal_payment') != 'none' && isValidPaymentGateway(config('cashier.digital_coin_payment')))
                    <div class="d-flex align-items-center choose-payment">
                        <div class="mr-auto pr-4">
                            <h4 class="font-weight-semibold mb-2">{{ trans('messages.payment.paypal_payment') }}</h4>
                            <p>{{ trans('messages.payment.paypal_payment.intro') }}</p>
                        </div>
                        <div class="text-right pl-4">
                            <div>
                                @include('helpers.form_control', [
                                    'type' => 'radio2',
                                    'name' => 'payment_method',
                                    'value' => '',
                                    'label' => '',
                                    'help_class' => 'setting',
                                    'rules' => ['payment_method' => 'required'],
                                    'options' => [
                                        ['value' => config('cashier.paypal_payment'), 'text' => ''],
                                    ],
                                ])
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="sub-section">
                <div class="row">
                    <div class="col-md-4">
                        <button link-method="POST"
                            class="btn btn-mc_primary">
                                {{ trans('messages.payment.proceed_with_payment') }}
                        </button>
                    </div>
                    <div class="col-md-8">
                        {!! trans('messages.payment.agree_service_intro', ['plan' => $plan->name]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    <script>
        $('.proceed_with_payment').on('submit', function(e) {
            if (!$('.choose-payment-methods>div [type=radio]:checked').length) {
                e.preventDefault();

                swal({
                    title: '{{ trans('messages.subscription.no_payment_method_selected') }}',
                    text: "",
                    confirmButtonColor: "#00695C",
                    type: "error",
                    allowOutsideClick: true,
                    confirmButtonText: LANG_OK,
                    customClass: "swl-success",
                    html: true
                });
            }                
        });

        $('.choose-payment-methods>div').on('click', function() {
            $(this).find('[type=radio]').prop('checked', true);

            $('.choose-payment-methods>div').removeClass('current');
            $('.choose-payment-methods>div [type=radio]:checked').closest('.choose-payment').addClass('current');
        });

        $('.choose-payment-methods>div').removeClass('current');
        $('.choose-payment-methods>div [type=radio]:checked').closest('.choose-payment').addClass('current');        

        if ($('.choose-payment-methods>div [type=radio]').length == 1) {
            $('.choose-payment-methods>div').first().click();
        }
    </script>

@endsection