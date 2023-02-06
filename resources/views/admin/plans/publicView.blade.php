@extends('layouts.core.backend', [
	'menu' => 'plan',
])

@section('title', trans('messages.plans'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title" style="padding-bottom:0">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.plan.public_view.title') }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <div class="row mb-4">
        <div class="col-md-8"><p class="mb-0">{{ trans('messages.plan.public_view.desc') }}</p></div>
        <div class="col-md-4">
            <div class="text-end">
                <a target="_blank" href="{{ action("PlanController@publicListPage") }}" role="button" class="btn btn-primary modal-action">
                    <span class="material-symbols-rounded">open_in_new</span> {{ trans('messages.plan.view_public_page') }}
                </a>
            </div>
        </div>
    </div>

    @include('plans._publicList', [
        'plans' => $plans,
    ])

@endsection
