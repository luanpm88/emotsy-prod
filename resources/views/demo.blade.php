@extends('layouts.core.demo')

@section('title', trans('messages.demo'))

@section('content')

                <style>
                    .demo_pic {
                        opacity: 0.85;
                        transition: all 0.2s ease-in-out;
                    }
                    .demo_pic:hover, .demo_pic:focus {
                        opacity: 1;
                    }
                </style>

                <div class="text-center login-header mt-4" style="margin-bottom: 30px">
                    <a class="main-logo-big mb-2 d-block" href="{{ action('HomeController@index') }}">
                        <img width="300px" src="{{ URL::asset('images/logo_light_blue.svg') }}" alt="">
                    </a>
                    <h3 class="text-center text-muted2 fs-5" style="color: #aaa; margin-left: -20px">{{ trans('messages.demo') }}</h3>
                </div>

                <div class="row">
                    <div class="col-md-1">

                    </div>
                    <div class="col-md-5">
                        <h4 class="mb-1 text-center text-white fs-5 fw-600">{{ trans("messages.frontend") }}</h3>
                        <a target="_blank" class="demo_pic" href="{{ action("Controller@demoGo", ['view' => 'frontend']) }}">
                            <img style="max-width: 100%" src="{{ url("/images/demo_frontend.png") }}" />
                        </a>
                    </div>
                    <div class="col-md-5">
                        <h4 class="mb-1 text-center text-white fs-5 fw-600">{{ trans("messages.backend") }}</h3>
                        <a target="_blank" class="demo_pic" href="{{ action("Controller@demoGo", ['view' => 'backend']) }}">
                            <img style="max-width: 100%" src="{{ url("/images/demo_backend.png") }}" />
                        </a>
                    </div>
                </div>

@endsection
