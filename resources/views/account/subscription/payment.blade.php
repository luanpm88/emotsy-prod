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
    </div>
    
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8">
            
                <h2 class="mb-3 mt-0">{{ trans('messages.plan.review.title') }}</h2>

                @if (request()->user()->customer->getPaymentMethod() == null)
                    <p>{{ trans('messages.plan.review.do_not_have_payment_yet') }}</p>

                    <h4 class="mb-3 mt-0 font-weight-semibold">{{ trans('messages.subscription.choose_payment_method') }}</h4>
                    <p>{{ trans('messages.plan.review.select_payment_method') }}</p>
                @else
                    <p>{!! trans('messages.plan.review.pay_with_current_payment', [
                        'payment' => trans('messages.payments.' . request()->user()->customer->getPaymentMethod()['method'])
                    ]) !!}</p>

                    <form action="{{ action('AccountSubscriptionController@checkout') }}" method="GET" class="proceed_with_payment">
                        {{ csrf_field() }}  
                        <div class="text-left">
                            <input type="submit" name="checkout"
                                class="btn btn-primary btn-large bg-teal-800"
                                value="{{ trans('messages.proceed_to_checkout') }}"
                            >
                        </div>
                    </form>

                    <p class="mt-4">{!! trans('messages.plan.review.or_other_payment') !!}</p>
                @endif
                
                <form class="edit-payment" action="{{ action('AccountController@editPaymentMethod') }}"
                    method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="return_url" value="{{ action('AccountSubscriptionController@checkout') }}" />

                    <div class="sub-section mb-30 choose-payment-methods">      
                        @foreach(Acelle\Model\Setting::getEnabledPaymentGateways() as $gateway)
                            <div class="d-flex align-items-center choose-payment choose-payment-{{ $gateway }}">
                                <div class="text-right pl-2 pr-2">
                                    <div class="d-flex align-items-center form-group-mb-0">
                                        @include('helpers.form_control', [
                                            'type' => 'radio2',
                                            'name' => 'payment_method',
                                            'value' => request()->user()->customer->getPaymentMethod() ? request()->user()->customer->getPaymentMethod()['method'] : '',
                                            'label' => '',
                                            'help_class' => 'setting',
                                            'rules' => ['payment_method' => 'required'],
                                            'options' => [
                                                ['value' => $gateway, 'text' => ''],
                                            ],
                                        ])
                                        <div class="check"></div>
                                    </div>
                                </div>
                                <div class="mr-auto pr-4">
                                    <h4 class="font-weight-semibold mb-2">{{ trans('messages.frontend_payment.' . $gateway) }}</h4>
                                    <p class="mb-3">{{ trans('messages.frontend_payment.' . $gateway . '.desc') }}</p>
                                </div>                        
                            </div>
                        @endforeach
                    </div>
                    
                    @if (request()->user()->customer->getPaymentMethod() !== null)
                        <div class="sub-section">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="submit" name="new_payment"
                                        class="btn btn-mc_primary"
                                        value="{{ trans('messages.proceed_with_payment') }}"
                                    >
                                </div>
                                <div class="col-md-8">
                                    {!! trans('messages.payment.agree_service_intro', ['plan' => $plan->name]) !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="sub-section">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-right">
                                        <input type="submit" name="checkout"
                                            class="btn btn-primary btn-large bg-teal-800"
                                            value="{{ trans('messages.proceed_to_checkout') }}"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    {!! trans('messages.payment.agree_service_intro', ['plan' => $plan->name]) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </form>

                <form class="mt-4 pt-4" method="POST" action="{{ action('AccountSubscriptionController@abort') }}">
                    {{ csrf_field() }}
                    
                    <a href="javascript:;" onclick="$(this).closest('form').submit()"
                        class="text-muted mt-4"
                        style="font-size: 16px; text-decoration: underline;color:#333"
                    >{{ trans('messages.change_mind_cancel_subscription') }}</a>
                </form>
        </div>
        <div class="col-md-4">
            @php
                $currentBill = $subscription->getLatestBillingInfo();
            @endphp
            <div class="upcoming-bill-box">
                <div class="header">
                    <h4 class="mt-0 font-weight-semibold mb-1">{{ trans('messages.lastest_bill') }}</h4>
                    @if ($currentBill['total'] == 0)
                        <div class="m-0">{{ trans('messages.lastest_bill.no_fee_description', [
                            'plan' => $currentBill['plan']->name,
                        ]) }}</div>
                    @else
                        <div class="m-0">{{ trans('messages.lastest_bill.description', [
                            'plan' => $currentBill['plan']->name,
                        ]) }}</div>
                    @endif
                </div>
                <div class="body">
                    <div>
                        @foreach ($currentBill['bill'] as $item)
                            <div class="bill_item d-flex">
                                <div class="mr-auto">
                                    <div class="font-weight-semibold">{{ $item['name'] }}</div>
                                    @if(isset($item['plan'])) 
                                        <div class="mt-2">
                                            <div>
                                                {{ trans('messages.sending_total_quota_label') }}: {{ $item['plan']->displayTotalQuota() }}
                                            </div>
                                            <div>
                                                {{ trans('messages.max_lists_label') }}: {{ $item['plan']->displayMaxList() }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="font-weight-semibold">{{ format_price($item['amount'],  $currentBill['plan']->currency->format)}}</div>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                    <div>
                        <div class="total d-flex ">
                            <div class="mr-auto">
                                <div class="font-weight-semibold">{{ trans('messages.bill.estimated_total') }}</div>
                                <div>{{ trans('messages.bill.current_period_ends_on', ['date' => $currentBill['charge_on']]) }}</div>
                            </div>
                            <div class="bill-total">{{ format_price($currentBill['total'],  $currentBill['plan']->currency->format)}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                @include('account._payment_info')
            </div>
        </div>
    </div>
    <div class="row">
        
    </div>


    <script>
        $('.edit-payment').on('submit', function(e) {
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