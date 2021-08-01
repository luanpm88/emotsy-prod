@extends('layouts.core.dark')

@section('title', trans('messages.subscriptions'))

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
                @include('subscription._selectPlan')

                @include('subscription._billingInformation')

                <div class="card mt-2 subscription-step">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">3</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.payment_method.title') }}
                                </h5>
                                <p class="m-0 text-muted">{{ trans('messages.subscription.payment_method.is_free') }}</p>
                            </div>
                            <div class="ms-auto">
                                <span class="material-icons-outlined fs-4 text-success">
                                    task_alt
                                    </span>
                            </div>
                        </div>                        
                    </div>
                    
                    {{-- <div class="card-body">
                        <div class="row py-4">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <div class="alert alert-info">{!! trans('messages.payment.agree_service_intro.free') !!}</div>

                                <form class="" action="{{ action('SubscriptionController@confirmFree', [
                                    'invoice_uid' => $invoice->uid,
                                ]) }}"
                                    method="POST">
                                    {{ csrf_field() }}

                                    <input type="submit" name="new_payment"
                                        class="btn btn-primary bg-teal-800 py-2 fs-6 px-4 text-center rounded-3 shadow" style="width:100%"
                                        value="{{ trans('messages.subscription.get_started') }}"
                                    >
                                </form>
                            </div>
                        </div>
                    </div> --}}
                </div>

                {{-- @if ($invoice->type !== \Acelle\Model\Invoice::TYPE_RENEW_SUBSCRIPTION)
                    <div class="mt-4">
                        <a class="" link-method="POST" link-confirm="{{ trans('messages.invoice.cancel.confirm') }}"
                            href="{{ action('SubscriptionController@cancelInvoice', [
                                'invoice_uid' => $invoice->uid,
                            ]) }}">
                            <span class="material-icons-round me-1">
                                arrow_back
                            </span> {{ trans('messages.subscription.cancel_now_change_other_plan') }}
                        </a>
                    </div>
                @endif --}}
            </div>
            <div class="col-md-4">
                @include('invoices.bill', [
                    'bill' => $invoice->getBillingInfo(),
                ])

                @include('subscription._checkout')
            </div>
        </div>
    </div>
@endsection