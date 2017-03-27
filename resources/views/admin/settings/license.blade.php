@extends('layouts.backend')

@section('title', trans('messages.license'))

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
    <form action="{{ action('Admin\SettingController@license') }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="tabbable">

            @include("admin.settings._tabs")

            <div class="tab-content">
                @foreach ($settings as $name => $setting)
                    @if ($setting['cat'] == 'license')
                        @if ($current_license && $setting['value'] == $current_license)
                            <h4>{{ trans('messages.your_current_license') }}</h4>
                            <h5 class="text-info-800 text-bold">
                                <i class="icon-file-check"></i>
                                {{ $current_license }}
                                ({{ trans('messages.license_label_' . \Acelle\Model\Setting::get('license_type')) }})
                            </h5>
                        @endif

						<hr />

						{!! trans('messages.license_guide') !!}

						<hr />

                        @if (!$current_license)
                            <h4>{{ trans('messages.verify_license') }}</h4>
                        @else
                            <h4>{{ trans('messages.change_license') }}</h4>
                        @endif
                        <div class="row license-line">
                            <div class="col-md-6">
                                @include('helpers.form_control', [
                                    'type' => $setting['type'],
                                    'class' => (isset($setting['class']) ? $setting['class'] : "" ),
                                    'name' => $name,
                                    'value' => '',
                                    'label' => trans('messages.enter_license_and_click_verify'),
                                    'help_class' => 'setting',
                                    'options' => (isset($setting['options']) ? $setting['options'] : "" ),
                                    'rules' => Acelle\Model\Setting::rules(),
                                ])
                            </div>
                            <div class="col-md-6">
                                <br />
                                <div class="text-left">
                                    @if ($current_license)
                                        <button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.change_license') }}</button>
                                    @else
                                        <button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.verify') }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>


    </form>
@endsection
