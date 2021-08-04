@extends('layouts.core.backend_dark')

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
                                <p class="m-0 text-muted">{{ trans('messages.subscription.payment_method.subtitle') }}</p>
                            </div>
                        </div>                        
                    </div>
                    <div class="card-body py-4" style="padding-left: 72px;padding-right:72px">
                        <h3 class="mb-4">{!! trans('messages.invoice.pending') !!}</h3>  
                        <p> {!! trans('messages.invoice.pending.wording') !!}</p>

                        @if ($invoice->type !== \Acelle\Model\Invoice::TYPE_NEW_SUBSCRIPTION)
                            <a class="btn btn-secondary"
                                href="{{ action('SubscriptionController@index') }}">
                                <span class="material-icons-round me-1">
                                    arrow_back
                                </span> {{ trans('messages.return_back') }}
                            </a>
                        @endif
                    </div>
                </div>
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
