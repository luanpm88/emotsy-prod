@extends('layouts.core.backend', [
	'menu' => 'plan_group',
])

@section('title', $planGroup->name)

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PlanGroupController@index") }}">{{ trans('messages.plan_groups') }}</a></li>
        </ul>
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $planGroup->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plan_groups._menu', [
        'menu' => 'security',
    ])
    
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanGroupController@save', $planGroup->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-6">
                <div class="mc_section">
                    <h2>{{ trans('messages.plan_group.speed_limit') }}</h2>
                        
                    <p>{{ trans('messages.plan_group.speed_limit.intro') }}</p>
                        
                    <div class="sending-limit-box" data-url="{{ action('Admin\PlanGroupController@sendingLimit', $planGroup->uid) }}">
                        @include ('admin.plan_groups._sending_limit')
                    </div>
                    <p>{{ trans('messages.plan_group.process_limit.intro') }}</p>
                    <div class="boxing">
                        <div class="row">
                            <div class="col-md-12">
                                @include('helpers.form_control', ['type' => 'select',
                                    'name' => 'plan[options][max_process]',
                                    'value' => $planGroup->getOption('max_process'),
                                    'label' => trans('messages.max_number_of_processes'),
                                    'options' => \Acelle\Model\PlanGroup::multiProcessSelectOptions(),
                                    'help_class' => 'plan',
                                    'rules' => $planGroup->validationRules()['general'],
                                ])
                            </div>
                        </div>
                    </div>
                    <h2 class="text-semibold mt-4">{{ trans('messages.bounce_rate_theshold') }}</h2>
            
                    <p>{!! trans('messages.bounce_rate_theshold.wording') !!}</p>

                    @include('helpers.form_control', ['type' => 'select',
                        'name' => 'plan[options][bounce_rate_theshold]',
                        'value' => $planGroup->getOption('bounce_rate_theshold'),
                        'label' => trans('messages.bounce_rate_theshold.set_a_limit'),
                        'options' => \Acelle\Model\PlanGroup::bounceRateThesholdOptions(),
                        'help_class' => 'plan',
                        'rules' => $planGroup->validationRules()['general'],
                    ])
                    <hr>
                    <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                    <a href="{{ action('Admin\PlanGroupController@index') }}" role="button" class="btn btn-link">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
    
    <script>
        var PlansSecurity = {
            sendingLimitPopup: null,

            getBox: function() {
                return $('.sending-limit-box');
            },

            getSendingLimitPopup: function() {
                if (this.sendingLimitPopup == null) {
                    this.sendingLimitPopup = new Popup({
                        url: '{{ action('Admin\PlanGroupController@sendingLimit', $planGroup->uid) }}'
                    });
                }

                return this.sendingLimitPopup;
            }
        }

        $(function() {
        });
    </script>
@endsection
