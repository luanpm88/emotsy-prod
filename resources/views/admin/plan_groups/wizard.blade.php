@extends('layouts.popup.small')

@section('bar-title')
{{ trans('messages.plan_group.new_plan') }}
@endsection

@section('content')
    <div class="mc_section mb-0">
        <form id="createPlanWizard" action="{{ action('Admin\PlanGroupController@wizard') }}" method="POST">
            {{ csrf_field() }}
                
            <div class="row">
                <div class="col-md-12">    
                    <h2 class="mt-0">{{ trans('messages.plan_group.general.details') }}</h2>
            
                    <p>{{ trans('messages.plan_group.general.details.intro') }}</p>
                        
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
                    
                    <div id="billingCycleSelectContainer">
                        @include ('admin.plan_groups._billing_cycle', [
                            'plan' => $planGroup,
                        ])
                    </div>
                    
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

                    <div class="mb-2">
                        @include('helpers.form_control.checkbox', [
                            'name' => 'has_trial',
                            'value' => 'yes',
                            'label' => trans('messages.plan_group.has_trial_period'),
                            'attributes' => [
                                'class' => 'numeric'
                            ],
                        ])
                    </div>
                        

                    <div class="trial_settings">
                        <label class="mb-2">{{ trans('messages.plan_group.trial_setting') }}</label>
                        <div class="d-flex mb-4">
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
                            <div class="" style="width:100px">
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
                 </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-secondary me-2">{{ trans('messages.plan_group.wizard.next') }}</button>
                <a href="javascript:;" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.plan_group.wizard.cancel') }}</a>
            </div>
            
        </form>
    </div>

    
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

        PlansCustomBillingCycle.getSelector().on('change', function() {
            var value = $(this).val();

            if (value == 'custom') {
                PlansCustomBillingCycle.getPopup().load();
            }
        });


        $('#createPlanWizard').submit(function() {
            var form = $(this);
            
            // ajax load url
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
            }).done(function(response) {
                if (response.redirect) {
                    window.location = response.redirect;
                } else {
                    PlansIndex.getWizardPopup().loadHtml(response);
                }
            });
            
            return false;
        });

        PlansCustomBillingCycle.getSelector().on('change', function() {
            var value = $(this).val();

            if (value == 'custom') {
                PlansCustomBillingCycle.getPopup().load();
            }
        });
    })
        

    var PlansCustomBillingCycle = {
        popup: null,

        getPopup: function() {
            if (this.popup == null) {
                this.popup = new Popup({
                    url: '{{ action('Admin\PlanGroupController@billingCycle', '00') }}',
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
