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

    @include("account._menu", ['tab' => 'subscription'])

    <div class="row">
        <div class="col-md-6">
            <h2>{!! trans('messages.invoice.pending') !!}</h2>  

            <div class="alert alert-info bg-grey-light">

                @if (request()->user()->customer->getPreferredPaymentGateway() !== null)
                    {!! trans('messages.invoice.pending.wording') !!}
                    <a href="{{ request()->user()->customer->getPreferredPaymentGateway()->getCheckoutUrl($invoice) }}" class="btn btn-mc_primary mt-4">{{ trans('messages.payment.check_payment') }}</a>
                @else
                    {!! trans('messages.invoice.pending.no_payment') !!}
                    <a href="{{ action('AccountController@editPaymentMethod', [
                        'redirect' => action('AccountSubscriptionController@payment'),
                    ]) }}" class="btn btn-mc_primary mt-4 payment-method-edit">{{ trans('messages.payment.update_payment') }}</a>

                    <script>
                        var paymentPopup = new Popup();
                        
                        $('.payment-method-edit').click(function(e) {
                            e.preventDefault();
                            var url = $(this).attr('href');

                            paymentPopup.load(url);
                        });
                    </script>
                @endif
            </div>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-4">
            @include('invoices.bill', [
                'bill' => $invoice->getBillingInfo(),
            ])
        </div>
    </div>
@endsection