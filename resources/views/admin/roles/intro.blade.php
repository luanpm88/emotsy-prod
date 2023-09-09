@extends('layouts.core.frontend', [
	'menu' => 'role',
])

@section('title', trans('messages.campaigns'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="material-symbols-rounded">format_list_bulleted</span> {{ trans('messages.roles') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <h1>{{ trans('messages.role.how_it_works') }}</h1>

    <div class="row">
        <div class="col-md-9 pe-md-5">
            <div>
                {!! trans('messages.role.intro') !!}

                <div class="mt-4 border rounded p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-4 pe-2">
                            <span class="material-symbols-rounded" style="font-size:40px;">bubble_chart</span>
                        </div>
                        <div>
                            <p class="fw-semibold mb-2">{{ trans('messages.role.announce.title') }}</p>
                            <p>
                                {{ trans('messages.role.announce.intro') }}
                            </p>
                        </div>
                        <div class="ps-5">
                            <a link-action="demo" href="" class="btn btn-default text-nowrap">{{ trans('messages.role.announce.setup_your_roles') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       <div class="col-md-3">
            <div>
                <div class="list-group style-2">
                    <a link-action="demo" href="#" class="list-group-item list-group-item-action active" aria-current="true">
                        {{ trans('messages.role.introduction') }}
                    </a>
                    <a link-action="demo" href="#" class="list-group-item list-group-item-action">{{ trans('messages.role.announce.setup_your_roles') }}</a>
                    <a link-action="demo" href="#" class="list-group-item list-group-item-action">{{ trans('messages.role.track_campaign_replies') }}</a>
                </div>
            </div>
       </div>
    </div>

    <div class="row">
        <div class="col-12">

        </div>
    </div>

    <script>
        var rolesIntro = {
            showDemoAlert: function() {
                new Dialog('alert', {
                    message: '{{ trans('messages.operation_not_allowed_in_demo') }}',
                });
            }
        };

        $(function() {
            // 
            $('[link-action="demo"]').on('click', function(e) {
                e.preventDefault();

                rolesIntro.showDemoAlert();
            });
        });
    </script>
@endsection
