@extends('layouts.core.backend', [
	'menu' => 'plan_group',
])

@section('title', $planGroup->name)

@section('head')
    <link href="{{ AppUrl::asset('core/rangeslider/css/bootstrap-slider.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ AppUrl::asset('core/rangeslider/bootstrap-slider.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PlanGroupController@index") }}">{{ trans('messages.plan_groups') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action('Admin\PlanGroupController@sendingServer', [ 'uid' => $planGroup->uid ]) }}">
                    {{ $planGroup->name }}
                </a>
            </li>
        </ul>
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $planGroup->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plan_groups._menu', [
        'menu' => 'sending_server',
    ])
    
    <div class="mc-section">
        <div class="announce_box">
            <div class="row flex-center">
                <div class="col-md-8">
                    <span class="material-symbols-rounded announce_box-icon">corporate_fare</span>
                    <label>{{ trans('messages.plan_group.option.delivery_setting') }}</label>
                    <h4>{{ trans('messages.plan_group.option.system_s_sending_server') }}</h4>
                    <p>{{ trans('messages.plan_group.option.system_s_sending_server.intro') }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <a class="btn btn-secondary mr-20 change-server-type-button" modal-size="lg" href="{{ action('Admin\PlanGroupController@sendingServerOption', [
                        'uid' => $planGroup->uid]) }}">
                            {{ trans('messages.plan_group.option.change') }}</a>
                </div>
            </div>
        </div>
            
        <div class="row flex-center">
            <div class="col-md-8">
                <h2 class="mc-h2 mt-0 mb-10">
                    <span class="text-semibold">{{ trans('messages.plan_group.sending_servers.listing') }}</span>
                </h2>
                <p>{{ trans('messages.plan_group.sending_servers.list_intro') }}</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ action('Admin\PlanGroupController@addSendingServer', [ 'id' => $planGroup->uid ]) }}" class="btn btn-primary me-1 bg-grey add-server-button">
                    {{ trans('messages.plan_group.sending_servers.add') }}
                </a>
            </div>
        </div>
        
        @if(!$planGroup->plansSendingServers()->count())
            <div class="empty-list">
                <span class="material-symbols-rounded">dns</span>
                <span class="line-1">
                    {{ trans('messages.sending_server_empty_line_1') }}
                </span>
            </div>
        @else
            <div class="sending-servers">
                <ul class="mc_list mt-20">
                    @foreach ($planGroup->plansSendingServers as $planGroupsendingServer)
                        <li>
                            <content width="50%">
                                @php
                                    $server = $planGroupsendingServer->sendingServer->mapType();
                                @endphp
                                @if ($server->isExtended())
                                    <span class="mc_list_media mc-server-avatar shadow-sm rounded server-avatar mr-0" style="background: url({{ $server->getIconUrl()  }}) top left/36px 36px no-repeat transparent;">
                                        <span class="material-symbols-rounded"></span></span>
                                @else
                                    <span class="mc_list_media mc-server-avatar shadow-sm rounded server-avatar server-avatar-{{ $planGroupsendingServer->sendingServer->type }} mr-0">
                                        <span class="material-symbols-rounded"></span></span>
                                @endif
                                <h4>{{ $planGroupsendingServer->sendingServer->name }}
                                    @if ($planGroupsendingServer->isPrimary())
                                        &nbsp;&nbsp;<span class="badge badge-info bg-grey">{{ trans('messages.sending_servers.primary') }}</span>
                                    @endif
                                </h4>
                                <p>
                                    {!! trans('messages.sending_server.speed', ['limit' => $planGroupsendingServer->sendingServer->displayQuotaHtml()]) !!}
                                </p>
                            </content>
                            <stat>
                                <div class="single-stat-box pull-left">
                                    <span class="stat-head percent-list">{{ $planGroupsendingServer->showFitness() }}%</span>
                                    <br />
                                    <span class="text-muted xtooltip" title="{{ trans("messages.sending_servers.fitness.explain") }}">{{ trans("messages.sending_servers.fitness") }}
                                    @if ($planGroup->plansSendingServers()->count() > 1)
                                        - <a modal-size="lg" class="edit-fitness-button" href="{{ action('Admin\PlanGroupController@fitness', ['id' => $planGroup->uid]) }}">{{ trans('messages.edit') }}</a></span>
                                    @endif
                                </div>
                            </stat>
                            <actions style='width: 30%; text-align: right'>                                
                                @if (!$planGroupsendingServer->isPrimary())
                                    <a link-method="POST" href="{{ action('Admin\PlanGroupController@setPrimarySendingServer', ['id' => $planGroup->uid, 'sending_server_uid' => $planGroupsendingServer->sendingServer->uid]) }}" class="btn btn-primary">{{ trans('messages.sending_servers.set_primary') }}</a>
                                @endif
                                @if (Auth::user()->admin->can('update', $planGroupsendingServer->sendingServer))
                                    
                                    @if ($server->isExtended())
                                        <a href="{{ $server->getEditUrl() }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                                    @else
                                        <a href="{{ action('Admin\SendingServerController@edit', ["id" => $planGroupsendingServer->sendingServer->uid, "type" => $planGroupsendingServer->sendingServer->type]) }}" data-popup="tooltip" title="{{ trans('messages.edit') }}" role="button" class="btn btn-secondary">{{ trans('messages.edit') }}</a>
                                    @endif
                                @endif
                                <a link-method="POST" href="{{ action('Admin\PlanGroupController@removeSendingServer', ['id' => $planGroup->uid, 'sending_server_uid' => $planGroupsendingServer->sendingServer->uid]) }}" class="btn btn-warning">{{ trans('messages.remove') }}</a>
                            </actions>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script>
        var ChangeTypePopup;
        var AddServerPopup;
        var EditFitnessPopup;

        $(function() {
            $('.change-server-type-button').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                ChangeTypePopup = new Popup({
                    url: url
                });
                ChangeTypePopup.load();
            });

            $('.add-server-button').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                AddServerPopup = new Popup({
                    url: url
                });
                AddServerPopup.load();
            });
            
            $('.edit-fitness-button').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                EditFitnessPopup = new Popup({
                    url: url
                });
                EditFitnessPopup.load();
            });
        });
    </script>
    
    @if ($planGroup->primarySendingServer())
        @php
            $server = $planGroup->primarySendingServer()->mapType();
        @endphp
        <div class="mc-section">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="mc-h2 mt-0">
                        <span class="text-semibold">{{ trans('messages.plan_group.sending_identify') }}</span>
                    </h2>
                    @if ($server->isExtended())
                        <p>{!! trans('messages.plan_group.sending_identify.intro.sendmail') !!}</p>
                    @else
                        <p>{!! trans('messages.plan_group.sending_identify.intro.' . $planGroup->primarySendingServer()->type) !!}</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-box pml-table table-log mt-10">
                        <tbody>
                            <tr>
                                <th>{{ trans('messages.sending_server.domain_email') }}</th>
                                <th>{{ trans('messages.sending_server.server') }}</th>
                                <th>{{ trans('messages.sending_server.date_added') }}</th>
                                <th>{{ trans('messages.sending_server.status') }}</th>
                                <th>{{ trans('messages.sending_server.action') }}</th>
                            </tr>
                        </tbody>
                        <tbody>
                            @forelse ($planGroup->getVerifiedIdentities() as $domain)
                                <tr class="odd">
                                    <td>
                                        <strong>{{ $domain }}</strong>
                                        <div class="text-muted2">{{ trans('messages.identity.supported') }}</div>
                                    </td>
                                    <td>
                                        <span>{{ $planGroup->primarySendingServer()->name }}</span>
                                    </td>
                                    <td>
                                        <span>
                                            {{ Auth::user()->admin->formatDateTime($planGroup->primarySendingServer()->updated_at, 'datetime_full') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-flat bg-active">{{ trans('messages.sending_identity.status.active') }}</span>
                                    </td>
                                    <td>
                                        <a class="direct-link"
                                          confirm-button='{{ trans('messages.go_to') }}'
                                          link-confirm="{{ trans('messages.go_to_sending_server_page') }}"
                                          href="{{ action('Admin\SendingServerController@edit', ["id" => $planGroup->primarySendingServer()->uid, "type" => $planGroup->primarySendingServer()->type]) }}">
                                            <input type="checkbox" name="options[domains][]" value="{{ $domain }}" class="switchery"
                                                checked readonly
                                            />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">
                                        {{ trans('messages.subscription.logs.empty') }}
                                    </td>
                                </te>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
