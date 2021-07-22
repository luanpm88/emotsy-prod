@extends('layouts.core.frontend')

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
            <h2 class="mb-3 mt-0">{{ $invoice->title }}</h2>

            <p>
                {{ trans('messages.subscription.thanks.click_to_start') }}
            </p>

            <form class="" action="{{ action('AccountSubscriptionController@confirmFree', [
                'invoice_uid' => $invoice->uid,
            ]) }}"
                method="POST">
                {{ csrf_field() }}

                <input type="submit" name="new_payment"
                    class="btn btn-primary bg-teal-800 py-3 px-4"
                    value="{{ trans('messages.subscription.get_started') }}"
                >

                <div class="row">
                    <div class="mt-4 col-md-8">{!! trans('messages.payment.agree_service_intro') !!}</div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            @include('invoices.bill', [
                'bill' => $invoice->getBillingInfo(),
            ])
        </div>
    </div>
    <div class="row">
        
    </div>

@endsection