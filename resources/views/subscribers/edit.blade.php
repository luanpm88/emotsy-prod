@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.create_subscriber'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')
    @include("lists._menu")

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="sub-section">
                <h3 class="text-semibold"><i class="icon-pencil"></i> {{ trans('messages.edit_subscriber') }}</h2>
                <form action="{{ action('SubscriberController@update', ['list_uid' => $list->uid, "uid" => $subscriber->uid]) }}" method="POST" class="form-validate-jqueryz">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PATCH">

                    @include("subscribers._form")

                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                    <a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>

                <form>
            </div>

            <div class="sub-section">
                <h3 class="text-semibold">Email Verification</h2>
                <p>The email address <strong>minhnghivn@gmail.com</strong> has not been verified yet. Choose a verification service below and click Verify button</p>
                <form enctype="multipart/form-data" action="{{ action('SubscriptionController@store') }}" method="POST" class="form-validate-jqueryz subscription-form">
                    {{ csrf_field() }}

                    @include('helpers.form_control', [
                        'type' => 'select_ajax',
                        'class' => 'subsciption-plan-select hook',
                        'name' => 'plan_uid',
                        'selected' => [],
                        'help_class' => 'subscription',
                        'rules' => [],
                        'url' => action('PlanController@select2'),
                        'placeholder' => trans('messages.select_plan')
                    ])
                    <div class="ajax-detail-box" data-url="{{ action('SubscriptionController@preview') }}" data-form=".subscription-form">
                    </div>
                </form>
                <p>
                    <a class="btn bg-grey-300" href="{{ action("AccountController@renewToken") }}">Verify Email</a>
                </p>
            </div>

			<div class="sub-section">
                <h3 class="text-semibold">Email Verification</h2>
                <p>The email address <strong>minhnghivn@gmail.com</strong> has been verified by <strong>EVerify.com</strong> at <strong>11:00 AM Jan 2017</strong></p>
                <p>
                    <a class="btn bg-grey-300" href="{{ action("AccountController@renewToken") }}">Reset Verification Status</a>
                </p>
            </div>

			<div class="sub-section">
                <h3 class="text-semibold">Email Verification</h2>
                <p>Warning: <strong>minhnghivn@gmail.com</strong> is NOT a valid email address, as verified by <strong>gaugau.com</strong></p>
                <p>
                    <a class="btn bg-grey-300" href="{{ action("AccountController@renewToken") }}">Reset Verification Status</a>
                </p>
            </div>
        </div>
    </div>

@endsection
