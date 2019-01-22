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
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $plan->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plans._menu')
    
    <div class="row">
        <div class="col-md-6">
            <form enctype="multipart/form-data" action="{{ action('Admin\PlanController@general', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
                {{ csrf_field() }}                
                <div class="mc_section">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'name' => 'name',
                        'value' => $plan->name,
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules()
                    ])
                    
                    @include('helpers.form_control', [
                        'class' => 'numeric',
                        'type' => 'text',
                        'name' => 'price',
                        'value' => $plan->price,
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules()
                    ])
                    
                    @include('helpers.form_control', [
                        'type' => 'select_ajax',
                        'name' => 'currency_id',
                        'label' => trans('messages.currency'),
                        'selected' => [
                            'value' => $plan->currency_id,
                            'text' => is_object($plan->currency) ? $plan->currency->displayName() : ''
                        ],
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules(),
                        'url' => action('Admin\CurrencyController@select2'),
                        'placeholder' => trans('messages.select_currency')
                    ])
                    
                    <div class="row">
                        <div class="col-md-6">
                            @include('helpers.form_control', [
                                'class' => 'numeric',
                                'type' => 'text',
                                'name' => 'frequency_amount',
                                'value' => $plan->frequency_amount,
                                'help_class' => 'plan',
                                'rules' => $plan->generalRules(),
                            ])
                        </div>
                        <div class="col-md-6">                        
                            @include('helpers.form_control', ['type' => 'select',
                                'name' => 'frequency_unit',
                                'value' => $plan->frequency_unit,
                                'options' => $plan->timeUnitOptions(),
                                'help_class' => 'plan',
                            ])
                        </div>
                    </div>
                    
                    @include('helpers.form_control', [
                        'type' => 'select',
                        'class' => '',
                        'name' => 'color',
                        'value' => $plan->color,
                        'help_class' => 'admin',
                        'options' => $plan->colors("color"),
                        'rules' => '',
                    ])
                    
                    @include('helpers.form_control', [
                        'class' => '',
                        'type' => 'checkbox2',
                        'name' => 'tax_billing_required',
                        'value' => $plan->tax_billing_required,
                        'options' => [false,true],
                        'help_class' => 'plan',
                        'rules' => $plan->generalRules()
                    ])                    
                </div>
                <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\PlanController@index') }}" type="button" class="btn btn-mc_inline">
                    {{ trans('messages.cancel') }}
                </a>
            </form>
        </div>
    </div>
@endsection
