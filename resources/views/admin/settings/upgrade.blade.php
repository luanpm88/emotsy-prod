@extends('layouts.backend')

@section('title', 'Upgrade Manager')

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-gear"><i class="icon-key"></i> {{ trans('messages.license') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <div class="tabbable">
        @include("admin.settings._tabs")

        <div class="tab-content">
            <div class="row">
				<div class="col-md-6">
                    <div class="sub-section">
                        <h3>Your current version</h3>
                        <p>Your are running on Acelle Mail <strong>v3.0.0-p13</strong>, released on <strong>Jan, 10th, 2017</strong>. You can upload the latest patch and upgrade to the latest version of Acelle Mail</p>
                        <form>
                            <button class="btn bg-teal">Upload new version</button>
                        </form>
                    </div>

                    <div class="sub-section">
                        <h3>Upgrade confirmation</h3>
                        <p>Your are running on Acelle Mail <strong>v3.0.0-p13</strong>, released on <strong>Jan, 10th, 2017</strong><br>The newer version is <strong>v3.0.0-p4</strong>, released on <strong>April, 10th 2017</strong></strong><br>Are you sure you want to upgrade?</p>
                        <form>
                            <button class="btn">Yes, Upgrade</button>
                            <button class="btn">No, Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
