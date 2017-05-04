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
                    <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                    @include("subscribers._form")

                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                    <a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>

                </form>
            </div>
            @if (is_null($subscriber->emailVerification))
                <div class="sub-section">
                    <h3 class="text-semibold">Email Verification</h2>
                    <p>The email address <strong>{{ $subscriber->email }}</strong> is not verified. Choose a verification service below and click Verify button</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@startVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jqueryz subscription-form">
                        {{ csrf_field() }}

                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'email_verification_server_id',
                            'value' => '',
                            'options' => \Auth::user()->customer->emailVerificationServerSelectOptions(),
                            'help_class' => 'verification',
                            'rules' => [],
                            'include_blank' => trans('messages.select_email_verification_server')
                        ])
                        <div class="text-left">
                            <button class="btn bg-teal mr-10"> {{ trans('messages.verify') }}</button>
                        </div>
                    </form>
                </div>
            @elseif ($subscriber->emailVerification->isDeliverable())
                <div class="sub-section">
                    <h3 class="text-semibold">Email Verification</h2>
                    <p>The email address <strong>{{ $subscriber->email }}</strong> has been verified by <strong>EVerify.com</strong> at <strong>11:00 AM Jan 2017</strong></p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jqueryz subscription-form">
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn bg-teal mr-10">Reset Verification Status</button>
                        </div>
                    </form>
                </div>
            @elseif ($subscriber->emailVerification->isUndeliverable())
                <div class="sub-section">
                    <h3 class="text-semibold">Email Verification</h2>
                    <p>Warning: <strong>{{ $subscriber->email }}</strong> is NOT a valid email address, as verified by <strong>gaugau.com</strong>. Please consider removing the email address from your list</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jqueryz subscription-form">
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn bg-teal mr-10">Reset Verification Status</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

@endsection
