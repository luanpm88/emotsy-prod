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
                <h3 class="text-semibold">Subscribe to a plan</h3>

                <p>Thank you for signing up with Acelle Mail. You are not currently subscribed to the any plan yet. Below are the available plans for you to choose from:</p>
                <div class="form-groups-bottom-0">
                    @include('helpers.form_control', [
                        'type' => 'select',
                        'class' => '',
                        'name' => 'subscription_uid',
                        'value' => '$subscription->uid',
                        'help_class' => 'subscription',
                        'options' => collect([ ['value' => 1, 'text' => 'Premium Plan'], ['value' => 2, 'text' => 'Super PRO Plan'] ]),
                        'rules' => []
                    ])
                </div>
                <br>
                <p><a class="btn bg-grey-300" href="{{ action("AccountController@renewToken") }}">Plan details & checkout</a></p>

            </div>
        </div>
    </div>

@endsection
