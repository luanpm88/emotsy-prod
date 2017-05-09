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
					@if (isset($failed))
						<p class="alert alert-warning">
							Something went wrong while upgrading Acelle Mail
						</p>

						<h3>Upgrade in progress</h3>
						<p>Some files cannot be updated. Please check and make sure the following files are writable by the web application, then try again</p>
						<p>
							<pre>{!! implode("\n", $failed) !!}</pre>
						</p>
						<p>
							<a link-confirm="Acelle Mail is going to upgrade the application. Please click the confirm button to proceed" href="#" type="button" class="btn bg-primary btn-icon" link-method="POST">
								Yes, upgrade now
							</a>
							<a link-confirm="Are you sure you want to cancel the upgrade?" href="#" type="button" class="btn bg-grey btn-icon">
								No, cancel
							</a>
						</p>
					@elseif ($manager->isNewVersionAvailable())
						<h3>Upgrade confirmation</h3>
						<p>Your are running on Acelle Mail <code>{{ $manager->getCurrentVersion() }}</code>, and a newer version is available: <code>{{ $manager->getNewVersion() }}</code><br>Do you want to proceed with the upgrade?</p>
						<p>
							<a link-confirm="Acelle Mail is going to upgrade the application. Please click the confirm button to proceed" href="{{ action('Admin\SettingController@doUpgrade') }}" type="button" class="btn bg-primary btn-icon" link-method="POST">
								Yes, upgrade now
							</a>
							<a link-confirm="Are you sure you want to cancel the upgrade?" href="#" type="button" class="btn bg-grey btn-icon">
								No, cancel
							</a>
						</p>
					@else
						<h3>Your current version</h3>
						<p>Your are currently running on Acelle Mail <code>v3.0.0-p13</code>. To upgrade your application, please download the latest version from Envato, then you can find the upgrade patch file located at <code>upgrade/patch-3.x.x.zip</code>, upload it using the form below</p>
						<form action="{{ action('Admin\SettingController@uploadApplicationPatch') }}" method="POST"  enctype="multipart/form-data">
							{{ csrf_field() }}

							@include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => '', 'name' => 'file', 'value' => 'Upload'])
							<button class="btn bg-teal">Upload new version</button>
						</form>
					@endif
                </div>
            </div>
        </div>
    </div>
@endsection
