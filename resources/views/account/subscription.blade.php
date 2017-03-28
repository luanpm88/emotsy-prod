@extends('layouts.frontend')

@section('title', trans('messages.api_token'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="active">{{ trans('messages.api_token') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-profile"></i> {{ Auth::user()->customer->displayName() }}</span>
        </h1>
    </div>

@endsection

@section('content')

    @include("account._menu")

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <h2 class="text-semibold">Subscription</h2>

            <div class="sub-section">
                <h3 class="text-semibold">Current plan</h3>

                <p>Thank you for doing your email marketing with Acelle Mail. You are currently subscribed to the <strong>Basic</strong> plan. Your subscription will expire in <strong>20 days</strong>, on <strong>April 20th, 2017</strong></p>
                <p>Please find your current account details below</p>
                <ul class="dotted-list topborder section">
                    <li>
                        <div class="unit size1of2">
                            <strong>Plan name</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>Basic XXX</mc:flag>
                        </div>
                    </li>
                    <li>
                        <div class="unit size1of2">
                            <strong>Start at</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>March, 1st 2017</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong>Next bill date</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag>March, 30th 2017</mc:flag>
                        </div>
                    </li>
                    <li class="selfclear">
                        <div class="unit size1of2">
                            <strong>Days remaining</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag><a href="mailto:minhnghivn@yahoo.com" target="_blank" rel="noopener noreferrer">minhnghivn@yahoo.com</a></mc:flag>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="sub-section">
                <h3>Available plans</h3>
                <p>You can renew your current plan or subscribe to a new one. Below are the available plans for you to choose from:</p>
                <div class="form-groups-bottom-0">
                    @include('helpers.form_control', [
                        'type' => 'select',
                        'class' => '',
                        'name' => 'subscription_uid',
                        'value' => '$subscription->uid',
                        'help_class' => 'subscription',
                        'options' => collect([ ['value' => 1, 'text' => 'Premium Plan'] ]),
                        'rules' => []
                    ])
                </div>
                <br>
                <p><a class="btn bg-primary-400" href="{{ action("AccountController@renewToken") }}">Plan details & checkout</a></p>

            </div>
        </div>
    </div>

@endsection
