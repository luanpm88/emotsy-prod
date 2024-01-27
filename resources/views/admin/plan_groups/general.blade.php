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
        'menu' => 'general',
    ])

    <form enctype="multipart/form-data" action="{{ action('Admin\PlanGroupController@save', $planGroup->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}                
        <div class="">
            <div class="row">
                <div class="col-md-7">
                    <h2>{{ trans('messages.plan_group.general.overview') }}</h2>
                    <!--
                    @include('elements._notification', [
                        'level' => 'info',
                        'message' => trans('messages.plan_group.info.subscriber_count', [
                            'count' => $planGroup->customersCount(),
                            'link' => action('Admin\SubscriptionController@index', ['plan_uid' => $planGroup->uid]),
                        ])
                    ])
                    -->
                    <p>{{ trans('messages.plan_group.general.details.intro') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    
                    <div class="stats-boxes">
                        <div class="width1of4 stats-box">
                            <h3>
                                
                                {{ $planGroup->name }}
                                
                            </h3>
                            <p>{{ trans('messages.plan_group') }}</p>
                        </div>
                        <div class="width1of4 stats-box">
                            <h3>
                                {{ $planGroup->displayPrice() }}
                            </h3>
                            <p>{{ trans('messages.plan_group.quota_time.wording', [
                                'amount' => number_with_delimiter($planGroup->frequency_amount, $precision = 0),
                                'unit' => trans('messages.' . $planGroup->frequency_unit),
                            ]) }}</p>
                        </div>
                        <div class="width1of4 stats-box">
                            <h3>
                                <a href="{{ action('Admin\PlanGroupController@quota', $planGroup->uid) }}">{{ $planGroup->displayTotalQuota() }}</a>
                            </h3>
                            <p>{{ trans('messages.plan_group.sending_credits') }}</p>
                        </div>
                        <div class="width1of4 stats-box">
                            <h3>
                                @if ($planGroup->getOption('sending_server_option') == \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_SYSTEM)
                                    @if ($planGroup->primarySendingServer())
                                        <a href="{{ action('Admin\PlanGroupController@sendingServers', $planGroup->uid) }}">
                                            @php
                                                $server = $planGroup->primarySendingServer()->mapType();
                                            @endphp
                                            @if ($server->isExtended())
                                                {{ $server->getTypeName() }}
                                            @else
                                                {{ trans('messages.sending_server.type.' . $planGroup->primarySendingServer()->type) }}
                                            @endif
                                        </a>
                                    @else
                                        <a class="text-warning" href="{{ action('Admin\PlanGroupController@sendingServers', $planGroup->uid) }}">
                                            {{ trans('messages.plan_group.sending_server.not_set') }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ action('Admin\PlanGroupController@sendingServer', $planGroup->uid) }}">
                                        {{ trans('messages.plan_group.sending_server.custom') }}
                                    </a>
                                @endif
                            </h3>
                            <p>{{ trans('messages.plan_group.delivery') }}</p>
                        </div>
                    </div>
                        
                    <h2>{{ trans('messages.plan_group.general.details') }}</h2>

                    @if (Acelle\Model\Plugin::isInstalled('acelle/chatgpt') && Acelle\Model\Plugin::getByName('acelle/chatgpt')->isActive())
                        @include('admin.chat._plan_group.name', [
                            'name' => 'plan[general][name]',
                            'value' => $planGroup->name,
                        ])

                        @include('admin.chat._plan_description', [
                            'name' => 'plan[general][description]',
                            'value' => $planGroup->description,
                        ])
                    @else             
                        @include('helpers.form_control', [
                            'type' => 'text',
                            'name' => 'plan[general][name]',
                            'label' => trans('messages.plan_group.name'),
                            'value' => $planGroup->name,
                            'help_class' => 'plan',
                            'rules' => $planGroup->generalRules()
                        ])

                        @include('helpers.form_control', [
                            'type' => 'text',
                            'name' => 'plan[general][description]',
                            'label' => trans('messages.plan_group.description'),
                            'value' => $planGroup->description,
                            'help_class' => 'plan',
                            'rules' => $planGroup->generalRules(),
                        ])
                    @endif
                    
                    @include('helpers.form_control', [
                        'class' => 'numeric',
                        'type' => 'text',
                        'name' => 'plan[general][price]',
                        'label' => trans('messages.plan_group.price'),
                        'value' => $planGroup->price,
                        'help_class' => 'plan',
                        'rules' => $planGroup->generalRules()
                    ])
                    
                    @include('helpers.form_control', [
                        'type' => 'select_ajax',
                        'name' => 'plan[general][currency_id]',
                        'label' => trans('messages.plan_group.currency'),
                        'selected' => [
                            'value' => $planGroup->currency_id,
                            'text' => $planGroup->currency ? $planGroup->currency->displayName() : ''
                        ],
                        'help_class' => 'plan',
                        'rules' => $planGroup->generalRules(),
                        'url' => action('Admin\CurrencyController@select2'),
                        'placeholder' => trans('messages.select_currency')
                    ])
                    
                    <div id="billingCycleSelectContainer">
                        @include ('admin.plan_groups._billing_cycle', [
                            'plan' => $planGroup,
                        ])
                    </div>

                    <div class="mb-2">
                        @include('helpers.form_control.checkbox', [
                            'name' => 'has_trial',
                            'value' => 'yes',
                            'label' => trans('messages.plan_group.has_trial_period'),
                            'attributes' => [
                                'class' => 'numeric',
                                'checked' => $planGroup->hasTrial() ? 'checked' : false
                            ],
                        ])
                    </div>
                        

                    <div class="trial_settings">
                        <label class="mb-2">{{ trans('messages.plan_group.trial_setting') }}</label>
                        <div class="d-flex">
                            <div class="me-3">
                                @include('helpers.form_control.number', [
                                    'name' => 'plan[general][trial_amount]',
                                    'value' => $planGroup->trial_amount,
                                    'attributes' => [
                                        'class' => 'numeric',
                                        'min' => '0',
                                    ],
                                ])
                            </div>
                            <div class="">
                                @include('helpers.form_control', [
                                    'type' => 'select',
                                    'name' => 'plan[general][trial_unit]',
                                    'value' => $planGroup->trial_unit,
                                    'options' => $planGroup->timeUnitOptions(),
                                    'help_class' => 'plan',
                                ])
                            </div>
                        </div>
                    </div>

                    @include('helpers.form_control', [
                        'class' => '',
                        'type' => 'checkbox2',
                        'name' => 'plan[general][own_tracking_domain_required]',
                        'label' => trans('messages.plan_group.own_tracking_domain_required'),
                        'help' => trans('messages.plan_group.own_tracking_domain_required.help'),
                        'value' => $planGroup->own_tracking_domain_required,
                        'options' => ['0','1'],
                        'help_class' => 'plan',
                        'rules' => $planGroup->generalRules()
                    ])
                 </div>
            </div>
        </div>
        <div class="mt-4">
            <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\PlanGroupController@index') }}" role="button" class="btn btn-link">
                {{ trans('messages.cancel') }}
            </a>
        </div>
    </form>

    <script>
        $(function() {    
            var manager = new GroupManager();
            manager.add({
                checkbox: $('[name="has_trial"]'),
                isChecked: function() {
                    return $('[name="has_trial"]').is(':checked');
                },
                box: $('.trial_settings'),
                textbox: $('[name="plan[general][trial_amount]"]'),
                currentValue: $('[name="plan[general][trial_amount]"]').val()
            });

            manager.bind(function(group) {
                var check = function() {
                    if (group.isChecked()) {
                        group.box.show();

                        group.textbox.prop('min', '1');
                        if (group.currentValue > 0) {
                            group.textbox.val(group.currentValue);
                        } else {
                            group.textbox.val(1);
                        }
                    } else {
                        group.box.hide();
                        group.currentValue = group.textbox.val();

                        group.textbox.prop('min', '0');
                        group.textbox.val(0);
                    }
                };

                group.checkbox.on('change', function() {
                    check();
                });

                check();
            });

            // events
            PlansCustomBillingCycle.events();
        })
            
    
        var PlansCustomBillingCycle = {
            popup: null,
    
            getPopup: function() {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: '{{ action('Admin\PlanGroupController@billingCycle', $planGroup->uid) }}',
                    });
                }
                return this.popup;
            },
    
            getSelectContainer: function() {
                return $('#billingCycleSelectContainer');
            },
    
            getSelector: function() {
                return $('#billingCycleSelectContainer select');
            },

            events: function() {
                var _this = this;
                this.getSelector().on('change', function() {
                    var value = $(this).val();
        
                    if (value == 'custom') {
                        _this.getPopup().load();
                    }
                });
            }
        }
    </script>
@endsection
