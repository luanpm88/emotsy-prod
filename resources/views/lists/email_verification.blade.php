@extends('layouts.frontend')

@section('title', $list->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    @if ($list->isVerificationRunning())
        <div class="sub-section">
            <h3 class="text-semibold">{{ trans('messages.verification_status') }}</h3>

            <div class="progress progress-lg mb-10">
                <div class="progress-bar progress-total active" style="width: {!! number_to_percentage($list->getVerifiedSubscribersPercentage()) !!}">
                    <span><span class="number">{!! number_to_percentage($list->getVerifiedSubscribersPercentage(), 0) !!}</span> {{ trans('messages.complete') }}</span>
                </div>
            </div>

            <p>{!! trans('messages.verification_process_running', [
                'verified' => $list->countVerifiedSubscribers(),
                'total' => \Acelle\Library\Tool::format_number($list->readCache('SubscriberCount')),
            ]) !!}</p>
            <p>
                <a class="btn bg-grey-300"
                     link-confirm="{{ trans('messages.stop_list_verification_confirm') }}" link-method="POST"
                    href="{{ action("MailListController@stopVerification", $list->uid) }}">
                    {{ trans('messages.stop_verification') }}
                </a>
            </p>
        </div>

        <hr>
    @else
        <div class="sub-section">
            <h3 class="text-semibold">{{ trans('messages.verification_status') }}</h3>

            <p>{!! trans('messages.verification_process_not_running', [
                'verified' => $list->countVerifiedSubscribers(),
                'total' => \Acelle\Library\Tool::format_number($list->readCache('SubscriberCount')),
            ]) !!}</p>
            <p>
                <a link-confirm="{{ trans('messages.reset_list_verification_confirm') }}" link-method="POST" class="btn bg-grey-600"
                    href="{{ action("MailListController@resetVerification", $list->uid) }}">
                        {{ trans('messages.reset_verification') }}
                </a>
            </p>
        </div>
        <div class="sub-section">
            <h3 class="text-semibold">{{ trans('messages.list_verification') }}</h3>
            <form enctype="multipart/form-data" action="{{ action('MailListController@startVerification', $list->uid) }}" method="POST" class="form-validate-jqueryz subscription-form">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-6">
                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'email_verification_server_id',
                            'label' => trans('messages.verify_your_list'),
                            'value' => '',
                            'options' => \Auth::user()->customer->emailVerificationServerSelectOptions(),
                            'help_class' => 'verification',
                            'rules' => [],
                            'include_blank' => trans('messages.select_email_verification_server')
                        ])

                        <div class="text-left">
                            <button class="btn bg-teal mr-10"> {{ trans('messages.verify') }}</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    @endif
@endsection
