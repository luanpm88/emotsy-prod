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
            <div class="col-md-2 text-right">
                <a href="{{ action('Admin\PlanController@addSendingServer', $plan->uid) }}" class="btn btn-primary bg-grey mc-modal-control">
                    {{ trans('messages.plan.sending_servers.add') }}
                </a>
            </div>
        </div>
        
        @if(!$plan->plansSendingServers()->count())
            <div class="empty-list">
                <i class="icon-server"></i>
                <span class="line-1">
                    {{ trans('messages.sending_server_empty_line_1') }}
                </span>
            </div>
        @else
            <div class="sending-servers">
                <ul class="mc_list mt-20">
                    @foreach ($plan->plansSendingServers as $planSendingServer)
                        <li>
                            <content>
                                <span class="mc_list_media server-avatar server-avatar-{{ $planSendingServer->sendingServer->type }} mr-0">
                                    <i class="icon-server"></i>
                                </span>
                                <h4>{{ $planSendingServer->sendingServer->name }}</h4>
                                <p>
                                    {{ trans('messages.sending_server.speed', ['limit' => $planSendingServer->sendingServer->displayQuota()]) }}
                                     · Fitness <span class="text-semibold"><b>{{ $planSendingServer->showFitness() }}</b>%</span>
                                </p>
                                <p>Domain verification Off · Email verification Off · Settings</p>
                            </content>                                        
                            <actions>
                                <a modal-size="lg" href="{{ action('Admin\PlanController@fitness', ['id' => $plan->uid]) }}" class="btn btn-mc_default mr-20 mc-modal-control">
                                    {{ trans('messages.plan.sending_servers.fitness.change') }}
                                </a>
                                <a link-method="POST" href="{{ action('Admin\PlanController@removeSendingServer', ['id' => $plan->uid, 'sending_server_id' => $planSendingServer->sendingServer->uid]) }}" class="btn btn-mc_default">{{ trans('messages.remove') }}</a>
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
