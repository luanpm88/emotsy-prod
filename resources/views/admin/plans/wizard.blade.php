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
                    
                    <div id="billingCycleSelectContainer">
                        @include ('admin.plans._billing_cycle', [
                            'plan' => $plan,
                        ])
                    </div>
                    
                    @include('helpers.form_control', [
                        'type' => 'select_ajax',
                        'name' => 'plan[general][currency_id]',
                        'label' => trans('messages.plan.currency'),
                        'selected' => [
                            'value' => $plan->currency_id,
                            'text' => is_object($plan->currency) ? $plan->currency->displayName() : ''
                        ],
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules(),
                        'url' => action('Admin\CurrencyController@select2'),
                        'placeholder' => trans('messages.select_currency')
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
        $('#createPlanWizard').submit(function() {
            var form = $(this);
            
            // ajax load url
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                dataType: 'html',
            }).done(function(response) {
                PlansIndex.getWizardPopup().loadHtml(response);
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
    }
</script>

@endsection
