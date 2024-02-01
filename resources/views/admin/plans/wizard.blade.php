@extends('layouts.popup.small')

@section('bar-title')
{{ trans('messages.plan.new_plan') }}
@endsection

@section('content')
    <div class="mc_section mb-0">
        <form id="createPlanWizard" action="{{ action('Admin\PlanController@wizard') }}" method="POST">
            {{ csrf_field() }}
                
            <div class="row">
                <div class="col-md-12">    
                    <h2 class="mt-0">{{ trans('messages.plan.general.details') }}</h2>
            
                    <p>{{ trans('messages.plan.general.details.intro') }}</p>

                    <div class="form-group"> 
                        <label class="form-label required" for="price">{{ trans('messages.plan.select_group') }}</label> 
                        <select class="form-select select w-auto" name="plan[general][plan_group_uid]">
                            @foreach (Acelle\Model\PlanGroup::all() as $planGroup)
                                <option value="{{ $planGroup->uid }}">{{ $planGroup->name }}</option>
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
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-secondary me-2">{{ trans('messages.plan.wizard.next') }}</button>
                <a href="javascript:;" class="btn btn-link me-2" data-dismiss="modal">{{ trans('messages.plan.wizard.cancel') }}</a>
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
                    url: '{{ action('Admin\PlanController@billingCycle', '00') }}',
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
