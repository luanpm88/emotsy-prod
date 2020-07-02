@extends('layouts.frontend')

@section('title', $domain->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li><a href="{{ action("TrackingDomainController@index") }}">{{ trans('messages.tracking_domains') }}</a></li>
        </ul>
        <h2>
            <span class="text-semibold"><i class="icon-earth"></i> {{ $domain->getUrl() }} </span>
            <span class="label label-primary bg-{{ $domain->status }}">
                {{ trans('messages.tracking_domain.status.' . $domain->status) }}
            </span>
        </h2>       
    </div>

@endsection

@section('content')
    
    <div class="row sub_section">
        <div class="col-sm-12 col-md-8">
            @if (!$domain->isVerified())
                <form action="{{ action('TrackingDomainController@verify', [ 'uid' => $domain->uid ]) }}" method="GET">
                    <div data-type="admin-notification" class="alert alert-warning" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                        <div style="display: flex; flex-direction: row; align-items: center;">
                            <div style="margin-right:15px">
                                <i class="lnr lnr-warning"></i>
                            </div>
                            <div style="padding-right: 40px">
                                <h4>Pending verification</h4>
                                <p>Please update your domain DNS records accordingly to the guideline below. Once you believe you're all set up, click the refresh button to check</p>
                            </div>
                        </div>
                        <button type="submit" class="btn bg-grey-600">Refresh now!</button>
                    </div>
                </form>
                <h2>DNS Setup</h2>
                <p>Tracking domains are used by the application in tracking email opens and link clicks. Once set up, all links in your emails are transformed and wrapped by a tracking domain. It helps improve your email reputation and make it less likely to be treated as spam hence improve your sending performance</p>

                <p>You need to add the following <strong>CNAME</strong> record to your domain DNS to verify your domain as well as to enable open/click tracking through the application.</p>
                <ul class="dotted-list topborder section section-flex">
                    <li style="font-size:16px">
                        <div class="size1of3">
                            <strong>{{ $domain->getFQDN() }}</strong><br><span style="font-size:14px;">Host name</span>
                        </div>
                        <div class="unit size1of3">
                            <strong>CNAME</strong><br><span style="font-size:14px;">Record type</span>
                        </div>
                        <div class="size1of3">
                            <strong>{{ $hostname }}</strong><br><span style="font-size:14px;">Value</span>
                        </div>
                    </li>
                </ul>
                <p style="margin-top: 20px"><i>Notice that DNS update may take from 5 minutes to a few hours to propagate</i></p>
            @else
                <div data-type="admin-notification" class="alert alert-success" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                    <div style="display: flex; flex-direction: row; align-items: center;">
                        <div style="margin-right:15px">
                            <i class="lnr lnr-checkmark-circle"></i>
                        </div>
                        <div style="padding-right: 40px">
                            <h4>{{ trans('messages.sender.status_info.verified') }}</h4>
                            <p>Your tracking domain is already verify and is ready for use. You can now set up your campaigns or automation to use a tracking domain to improve delivery performance</p>
                        </div>
                    </div>
                </div>

                <a type="button" class="btn btn-mc_inline" href="{{ action('TrackingDomainController@index') }} ">Go back</a>
            @endif
        </div>
    </div>

@endsection
