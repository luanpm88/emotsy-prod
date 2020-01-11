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
                <form enctype="multipart/form-data"  action="{{ action('SubscriberController@update', ['list_uid' => $list->uid, "uid" => $subscriber->uid]) }}" method="POST" class="form-validate-jqueryz">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="list_uid" value="{{ $list->uid }}" />
                    
                    <div class="d-flex align-items-top">
                        <div>
                            @include('helpers._upload',['src' => (isSiteDemo() ? 'https://i.pravatar.cc/300' : action('SubscriberController@avatar',  $subscriber->uid)), 'dragId' => 'upload-avatar', 'preview' => 'image'])
                        </div>
                        <div class="mt-20 hide">
                            <div class="dropdown">
                            <button class="btn btn-default bg-grey dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ trans('messages.subscribers.profile.action') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                <li><a href="#">{{ trans('messages.subscribers.profile.remove_subscriber') }}</a></li>
                                <li><a href="#">{{ trans('messages.subscribers.profile.manage_tags') }}</a></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="clear-both">{{trans("messages.basic_information")}}</h3>
                    @include("subscribers._form")

                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                    <a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>

                </form>
            </div>

            <div class="sub-section">
                <h3 class="text-semibold">{{ trans('messages.verification.title.email_verification') }}</h3>

                @if (is_null($subscriber->emailVerification))
                    <p>{!! trans('messages.verification.wording.verify', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email) ]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@startVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}

                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        @include('helpers.form_control', [
                            'type' => 'select',
                            'name' => 'email_verification_server_id',
                            'value' => '',
                            'options' => \Auth::user()->customer->emailVerificationServerSelectOptions(),
                            'help_class' => 'verification',
                            'rules' => ['email_verification_server_id' => 'required'],
                            'include_blank' => trans('messages.select_email_verification_server')
                        ])
                        <div class="text-left">
                            <button class="btn bg-teal mr-10"> {{ trans('messages.verification.button.verify') }}</button>
                        </div>
                    </form>
                @elseif ($subscriber->emailVerification->isDeliverable())
                    <p>{!! trans('messages.verification.wording.deliverable', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email), 'at' => sprintf("<strong>%s</strong>", $subscriber->emailVerification->created_at) ]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        {{ csrf_field() }}
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn bg-teal mr-10">{{ trans('messages.verification.button.reset') }}</button>
                        </div>
                    </form>
                @elseif ($subscriber->emailVerification->isUndeliverable())
                    <p>{!! trans('messages.verification.wording.undeliverable', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email)]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn bg-teal mr-10">{{ trans('messages.verification.button.reset') }}</button>
                        </div>
                    </form>
                @else
                    <p>{!! trans('messages.verification.wording.risky_or_unknown', [ 'email' => sprintf("<strong>%s</strong>", $subscriber->email), 'at' => sprintf("<strong>%s</strong>", $subscriber->emailVerification->created_at), 'result' => sprintf("<strong>%s</strong>", $subscriber->emailVerification->result)]) !!}</p>
                    <form enctype="multipart/form-data" action="{{ action('SubscriberController@resetVerification', ['uid' => $subscriber->uid]) }}" method="POST" class="form-validate-jquery">
                        <input type="hidden" name="list_uid" value="{{ $list->uid }}" />

                        <div class="text-left">
                            <button class="btn bg-teal mr-10">{{ trans('messages.verification.button.reset') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        @if(isSiteDemo())
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="d-flex align-items-top mt-5">
                    <h3 class="mr-auto">{{ trans('messages.automation.contact.activity_feed') }}</h3>
                    <div class="">
                        <div class="mt-10">
                            <div class="dropdown">
                            <button class="btn btn-default bg-grey dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ trans('messages.automation.contact.all_activities') }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                <li><a href="#">Open</a></li>
                                <li><a href="#">Click</a></li>
                                <li><a href="#">Subscribe</a></li>
                                <li><a href="#">Unsubscribe</a></li>
                                <li><a href="#">Updated</a></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="activity-feed mt-3">
                    <label class="date small font-weight-semibold mb-0 divider">Timeline</label>
                    
                    <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="lnr lnr-envelope bg-primary"></i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup receives email entitled "Follow up Email"
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="lnr lnr-clock mr-1"></i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="lnr lnr-envelope bg-primary"></i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup receives email entitled "Welcome to our list"
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="lnr lnr-clock mr-1"></i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="lnr lnr-clock bg-secondary"></i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                Wait for 24 hours before proceeding with the next event for user Ardella Goldrup
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="lnr lnr-clock mr-1"></i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="material-icons bg-warning">call_split</i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup reads email entitled "Welcome email"
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="lnr lnr-clock mr-1"></i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                                        <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">3 days ago</time>
                            <i class="lnr lnr-exit-up bg-success"></i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                User Ardella Goldrup subscribes to mail list, automation triggered!
                            </action>
                            <desc class="d-block small text-muted">
                                <i class="lnr lnr-clock mr-1"></i> Jan 07th, 2020 09:36
                            </desc>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
