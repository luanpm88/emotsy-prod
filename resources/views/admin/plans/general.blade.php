@extends('layouts.core.backend', [
	'menu' => 'plan',
])

@section('title', $plan->name)

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PlanController@index") }}">{{ trans('messages.plans') }}</a></li>
        </ul>
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $plan->planGroup->name }} - {{ $plan->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plans._menu', [
        'menu' => 'general',
    ])

    <form enctype="multipart/form-data" action="{{ action('Admin\PlanController@save', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}                
        <div class="">
            <div class="row">
                <div class="col-md-7">
                    <h2>{{ trans('messages.plan.general.overview') }}</h2>
                    <!--
                    @include('elements._notification', [
                        'level' => 'info',
                        'message' => trans('messages.plan.info.subscriber_count', [
                            'count' => $plan->customersCount(),
                            'link' => action('Admin\SubscriptionController@index', ['plan_uid' => $plan->uid]),
                        ])
                    ])
                    -->
                    <p>{{ trans('messages.plan.general.details.intro') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="stats-boxes">
                        <div class="width1of4 stats-box">
                            <h3>
                                
                                {{ $plan->planGroup->name }}
                                
                            </h3>
                            <p>{{ trans('messages.plan') }}</p>
                        </div>
                        <div class="width1of4 stats-box">
                            <h3>
                                {{ $plan->displayPrice() }}
                            </h3>
                            <p>{{ trans('messages.plan.quota_time.wording', [
                                'amount' => number_with_delimiter($plan->frequency_amount, $precision = 0),
                                'unit' => trans('messages.' . $plan->frequency_unit),
                            ]) }}</p>
                        </div>
                        <div class="width1of4 stats-box">
                            <h3>
                                <a href="{{ action('Admin\PlanController@quota', $plan->uid) }}">{{ $plan->displayTotalQuota() }}</a>
                            </h3>
                            <p>{{ trans('messages.plan.sending_credits') }}</p>
                        </div>
                        <div class="width1of4 stats-box">
                            <h3>
                                @if ($plan->getOption('sending_server_option') == \Acelle\Model\PlanGeneral::SENDING_SERVER_OPTION_SYSTEM)
                                    @if ($plan->primarySendingServer())
                                        <a href="{{ action('Admin\PlanController@sendingServers', $plan->uid) }}">
                                            @php
                                                $server = $plan->primarySendingServer()->mapType();
                                            @endphp
                                            @if ($server->isExtended())
                                                {{ $server->getTypeName() }}
                                            @else
                                                {{ trans('messages.sending_server.type.' . $plan->primarySendingServer()->type) }}
                                            @endif
                                        </a>
                                    @else
                                        <a class="text-warning" href="{{ action('Admin\PlanController@sendingServers', $plan->uid) }}">
                                            {{ trans('messages.plan.sending_server.not_set') }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ action('Admin\PlanController@sendingServer', $plan->uid) }}">
                                        {{ trans('messages.plan.sending_server.custom') }}
                                    </a>
                                @endif
                            </h3>
                            <p>{{ trans('messages.plan.delivery') }}</p>
                        </div>
                    </div>
                        
                    <h2>{{ trans('messages.plan.general.details') }}</h2>

                    <div class="form-group"> 
                        <label class="form-label required" for="price">{{ trans('messages.plan.select_group') }}</label> 
                        <select class="form-select select w-auto" name="plan[general][plan_group_uid]">
                            @foreach (Acelle\Model\PlanGroup::all() as $planGroup)
                                <option {{ $planGroup->id == $plan->plan_group_id ? 'selected' : '' }} value="{{ $planGroup->uid }}">{{ $planGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'plan[general][name]',
                        'label' => trans('messages.plan.name'),
                        'value' => $plan->name,
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules()
                    ])

                    @include('helpers.form_control', [
                        'class' => 'numeric',
                        'type' => 'text',
                        'name' => 'plan[general][price]',
                        'label' => trans('messages.plan.price'),
                        'value' => $plan->price,
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules()
                    ])
                 </div>
            </div>
        </div>
        <div class="mt-4">
            <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
            <a href="{{ action('Admin\PlanController@index') }}" role="button" class="btn btn-link">
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
                        url: '{{ action('Admin\PlanController@billingCycle', $plan->uid) }}',
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
