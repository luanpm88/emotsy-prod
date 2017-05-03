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

                </form>
            </div>
            <hr>
            @if (!$subscriber->isVerifed())
                <div class="sub-section">
                    <h3 class="text-semibold">{{ trans('messages.email_verification') }}</h2>
                    <p>
                        {!! trans('messages.email_not_verified_intro', [
                            'email' => $subscriber->email
                        ]) !!}
                    </p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@verify', $subscriber->uid) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}

                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'email_verification_server_uid',
                            'label' => '',
                            'value' => '',
                            'options' => \Auth::user()->customer->emailVerificationServerSelectOptions(),
                            'help_class' => 'verification',
                            'rules' => ['email_verification_server_uid' => 'required'],
                            'include_blank' => trans('messages.select_email_verification_server')
                        ])

                        <p>
                            <button class="btn bg-grey-300" type="submit">{{ trans('messages.verify_subscriber') }}</button>
                        </p>
                    </form>

                </div>
            @else
                <div class="sub-section">
                    <h3 class="text-semibold">{{ trans('messages.email_verification') }}</h2>
                    <p>
                        {!! trans('messages.email_verified_intro', [
                            'email' => $subscriber->email
                        ]) !!}
                        <?php $result = $subscriber->getVerificationResult() ?>
                        <span class="label label-flat bg-{{ $result }}">{{ trans('messages.email_verification_result_' . $result) }}</span>
                    </p>
                    <p>
                        <a link-confirm="{{ trans('messages.reset_subscriber_verification_confirm') }}" link-method="POST" class="btn bg-grey-600"
                            href="{{ action("SubscriberController@resetVerification", $subscriber->uid) }}">
                            {{ trans('messages.reset_subscriber_verification_status') }}
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>

@endsection
