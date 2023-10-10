@extends('layouts.core.backend', [
    'menu' => 'setting',
])

@section('title', trans('messages.license'))

@section('page_header')
    <style type="text/css">
        .alert:before {
            position: initial;
        }

    </style>

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-gear"><span class="material-symbols-rounded">vpn_key</span> {{ trans('messages.license') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <div class="tabbable">
        @include("admin.settings._tabs")

        <form action="{{ action('Admin\SettingController@license') }}" method="POST" class="form-validate-jqueryz">

            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-12 col-lg-8 col-sm-12">
                    <div class="tab-content">

                        @if ($license_error)
                            <div class="alert alert-danger">
                                {{ $license_error }}
                            </div>
                        @endif


                        @if ($license)
                            @php
                                if ($license->isActive()) {
                                    $style = 'alert-success';
                                    $title = '';
                                } elseif ($license->isExpired()) {
                                    $style = 'alert-danger';
                                    $title = trans('messages.support.expired.description');
                                }
                            @endphp

                            <div class="sub-section">
                                <h3>{{ trans('messages.license.your_license') }}</h3>
                                <p>{{ trans('messages.your_current_license') }} <strong>{{ $license->getType() }}</strong></p>
                                <div class="alert {{ $style }}" style="display: flex; flex-direction: row; align-items: center;">
                                    <div  title='{{$title}}' class="xtooltip" style="display: flex; flex-direction: row; align-items: center;">
                                        <p style="padding-left: 10px;padding-right: 40px">{{ $license->getLicenseNumber() }}{!! $license->isExpired() ? ' | <strong>'.trans('messages.support.expired.note').'</strong>' : '' !!}</p>
                                    </div>

                                    <a style="margin-left: auto" class="btn btn-secondary" href="{{ action('Admin\SettingController@licenseRemove') }}" link-confirm="{{ trans('messages.license.remove.confirm') }}" link-method="POST">
                                        <i class="material-symbols-rounded">delete</i>
                                        {{ trans('messages.license.remove') }}
                                    </a>
                                </div>
                            </div>

                        @else
                            <div class="sub-section">
                                <h3>{{ trans('messages.license.your_license') }}</h3>
                                <p> {{ trans('messages.license.no_license') }} </p>
                            </div>
                        @endif

                        <div class="sub-section">
                            <h3>{{ trans('messages.license.license_types') }}</h3>
                            {!! trans('messages.license_guide') !!}
                        </div>

                        <div class="sub-section">
                            @if (!$license)
                                <h3>{{ trans('messages.verify_license') }}</h3>
                            @else
                                <h3>{{ trans('messages.change_license') }}</h3>
                            @endif
                            <div class="row license-line">
                                <div class="col-md-8">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'license',
                                        'value' => (request()->license ? request()->license : ''),
                                        'label' => trans('messages.enter_license_and_click_verify'),
                                        'help_class' => 'setting',
                                        'rules' => Acelle\Model\Setting::rules(),
                                    ])
                                </div>
                                <div class="col-md-4">
                                    <br />
                                    <div class="text-left">
                                        @if ($license)
                                            <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.change_license') }}</button>
                                        @else
                                            <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.verify_license') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
