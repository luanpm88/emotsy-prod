@extends('layouts.backend')

@section('title', $plan->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li><a href="{{ action("Admin\PlanController@index") }}">{{ trans('messages.plans') }}</a></li>
        </ul>
        <h1 class="mc-h1 mb-0">
            <span class="text-semibold">{{ $plan->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <div class="mc-section">
        <div class="row">
            <div class="col-md-10">
                <h2 class="mc-h2 mt-0">
                    <span class="text-semibold">{{ trans('messages.plan.sending_servers.listing') }}</span>
                </h2>
                <p>{{ trans('messages.plan.sending_servers.intro') }}</p>
            </div>
            <div class="col-md-2">
                <a href="{{ action('Admin\PlanController@addSendingServer', $plan->uid) }}" class="btn btn-primary bg-grey modal-control">{{ trans('messages.plan.sending_servers.add') }}</a>
            </div>
        </div>
        
        @if(!Auth()->user()->admin->getAllSendingServers()->count())
            <div class="alert alert-danger mt-20">
                {!! trans('messages.plan_option.there_no_sending_server') !!}
            </div>
        @else
            
            <div class="sending-servers">
                <ul class="mc_list mt-20">
                    @foreach (Auth()->user()->admin->getAllSendingServers()->orderBy("name")->get() as $server)
                        <li>
                            <content>
                                <span class="mc_list_media server-avatar server-avatar-{{ $server->type }} mr-0">
                                    <i class="icon-server"></i>
                                </span>
                                <h4>{{ $server->name }}</h4>
                                <p>
                                    {{ trans('messages.sending_server.speed', ['limit' => $server->displayQuota()]) }}
                                     · Fitness <span class="text-semibold">100</span>
                                </p>
                                <p>Domain verification Off · Email verification Off · Settings</p>
                            </content>                                        
                            <actions>
                                <a href="" class="btn btn-mc_default mr-20">{{ trans('messages.edit') }}</a>
                                <a href="" class="btn btn-mc_default">{{ trans('messages.remove') }}</a>
                            </actions>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    
    <div class="mc-section">
        <h2 class="mc-h2 mt-0">
            <span class="text-semibold">{{ trans('messages.plan.sending_identify') }}</span>
        </h2>
    
        <p>{{ trans('messages.plan.sending_identify.intro') }}</p>
        
        @include('helpers.form_control', ['type' => 'mc_checkbox',
            'class' => '',
            'name' => '',
            'value' => '',
            'label' => trans('messages.use_all_sending_servers'),
            'options' => ['no','yes'],
            'help_class' => '',
            'rules' => []
        ])
    </div>
@endsection
