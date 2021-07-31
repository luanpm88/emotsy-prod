@extends('layouts.core.dark')

@section('title', trans('messages.subscriptions'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('menu_title')
    @include('subscription._title')
@endsection

@section('menu_right')
    @include('layouts.core._top_activity_log')
    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card subscription-step muted">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">1</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.plan_chosen', [
                                        'plan' => $invoice->getBillingInfo()['plan']->name
                                    ]) }}
                                </h5>
                                <p class="m-0 text-muted">{!! $invoice->getBillingInfo()['description'] !!}</p>
                            </div>
                            <div class="ms-auto">
                                <span class="material-icons-outlined fs-4 text-success">
                                    task_alt
                                    </span>
                            </div>
                        </div>
                            
                    </div>
                </div>

                <div class="card mt-2 subscription-step muted">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">2</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.billing_information.title') }}
                                </h5>
                                <p class="m-0 text-muted">{{ trans('messages.subscription.billing_information.subtitle') }}</p>
                            </div>
                            <div class="ms-auto">
                                <span class="material-icons-outlined fs-4 text-success">
                                    task_alt
                                    </span>
                            </div>
                        </div>
                            
                    </div>
                </div>

                <div class="card mt-2 subscription-step">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">3</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.payment_method.title') }}
                                </h5>
                                <p class="m-0 text-muted">{{ trans('messages.subscription.payment_method.subtitle') }}</p>
                            </div>
                        </div>                        
                    </div>
                    <div class="card-body py-4" style="padding-left: 72px;padding-right:72px">
                        <form class="edit-payment"
                            action="{{ action('AccountController@editPaymentMethod') }}"
                            method="POST">
                            {{ csrf_field() }}
            
                            <p>{{ trans('messages.payment.choose_new_payment_method_to_proceed') }}</p>
            
                            <input type="hidden" name="return_url" value="{{ action('SubscriptionController@payment', [
                                'invoice_uid' => $invoice->uid,
                            ]) }}" />

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="sub-section mb-30 choose-payment-methods">      
                                        @foreach(Acelle\Library\Facades\Billing::getEnabledPaymentGateways() as $gateway)
                                            <div class="choose-payment-method">
                                                <div class="d-flex pt-3 pb-3 pl-2 choose-payment choose-payment-{{ $gateway->getType() }}">
                                                    <div class="text-end pe-2">
                                                        <div class="d-flex align-items-center form-group-mb-0 pt-1" style="width: 30px">
                                                            @include('helpers.form_control', [
                                                                'type' => 'radio2',
                                                                'name' => 'payment_method',
                                                                'value' => request()->user()->customer->getPreferredPaymentGateway() ? request()->user()->customer->getPreferredPaymentGateway()->getType() : '',
                                                                'label' => '',
                                                                'help_class' => 'setting',
                                                                'rules' => ['payment_method' => 'required'],
                                                                'options' => [
                                                                    ['value' => $gateway->getType(), 'text' => ''],
                                                                ],
                                                            ])
                                                            <div class="check"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-auto pr-4">
                                                        <h5 class="font-weight-semibold mb-1">{{ $gateway->getName() }}</h5>
                                                        <p class="mb-0">{{ trans('messages.'.$gateway->getType().'.user.description') }}</p>
                                                    </div>    
                                                </div>
                                                                    
                                            </div>
                                            <div class="choose-payment-method">
                                                <div class="d-flex pt-3 pb-3 pl-2 choose-payment choose-payment-{{ $gateway->getType() }}">
                                                    <div class="text-end pe-2">
                                                        <div class="d-flex align-items-center form-group-mb-0 pt-1" style="width: 30px">
                                                            @include('helpers.form_control', [
                                                                'type' => 'radio2',
                                                                'name' => 'payment_method',
                                                                'value' => request()->user()->customer->getPreferredPaymentGateway() ? request()->user()->customer->getPreferredPaymentGateway()->getType() : '',
                                                                'label' => '',
                                                                'help_class' => 'setting',
                                                                'rules' => ['payment_method' => 'required'],
                                                                'options' => [
                                                                    ['value' => $gateway->getType(), 'text' => ''],
                                                                ],
                                                            ])
                                                            <div class="check"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-auto pr-4">
                                                        <h5 class="font-weight-semibold mb-1">{{ $gateway->getName() }}</h5>
                                                        <p class="mb-0">{{ trans('messages.'.$gateway->getType().'.user.description') }}</p>
                                                    </div>    
                                                </div>
                                                                    
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @include('invoices.bill', [
                    'bill' => $invoice->getBillingInfo(),
                ])

                @if (request()->user()->customer->getPreferredPaymentGateway() !== null)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="alert alert-info">{!! trans('messages.payment.agree_service_intro') !!}</div>
                            <form class="" action="{{ action('SubscriptionController@checkout') }}"
                                method="POST">
                                {{ csrf_field() }}
        
                                <input type="submit" name="new_payment"
                                    class="btn btn-primary py-2 fs-6 px-4 text-center rounded-3 shadow" style="width:100%"
                                    value="{{ trans('messages.subscription.checkout') }}"
                                >
                            </form>
                        </div>
                    </div>
                @endif

                @if ($invoice->type !== \Acelle\Model\Invoice::TYPE_RENEW_SUBSCRIPTION)
                    <div class="mt-4 small">
                        <a class="" link-method="POST" link-confirm="{{ trans('messages.invoice.cancel.confirm') }}"
                            href="{{ action('SubscriptionController@cancelInvoice', [
                                'invoice_uid' => $invoice->uid,
                            ]) }}">
                            <span class="material-icons-round me-1">
                                arrow_back
                            </span> {{ trans('messages.subscription.cancel_now_change_other_plan') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('.edit-payment').on('submit', function(e) {
                if (!$('.choose-payment-methods>div [type=radio]:checked').length) {
                    e.preventDefault();

                    new Dialog('alert', {
                        message: '{{ trans('messages.subscription.no_payment_method_selected') }}',
                        title: "{{ trans('messages.notify.error') }}"
                    });
                }
            });

            var manager = new GroupManager();

            $('.choose-payment-methods .choose-payment-method').each(function() {
                manager.add({
                    radio: $(this).find('input[name=payment_method]'),
                    box: $(this)
                });
            });

            manager.bind(function(group, others) {
                var doCheck = function() {
                    var checked = group.radio.is(':checked');
                    
                    if (checked) {
                        others.forEach(function(other) {
                            other.box.removeClass("current");
                        });
                        group.box.addClass("current");
                    } else {
                        group.box.removeClass("current");
                    }
                };

                group.radio.on('change', function() {
                    doCheck();
                });

                group.box.on('click', function() {
                    group.radio.prop('checked', true);

                    doCheck();
                });

                doCheck();
            });

            // 
            $('.other-payment-click').on('click', function() {
                $('.edit-payment').show();
                $('.current_payment').hide();
                $(this).hide();
            });
        });
        
    </script>

    <script>
        $('.other-payment-click').on('click', function() {
            $('.edit-payment').show();
            $('.current_payment').hide();
            $(this).hide();
        });
    </script>

@endsection