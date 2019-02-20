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
    
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanController@save', $plan->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-6">
                <div class="mc_section">
                    <h2>{{ trans('messages.plan.payment') }}</h2>
                        
                    <p>{{ trans('messages.plan.payment.intro') }}</p>
                        
                    @include('helpers.form_control', [
                        'type' => 'select',
                        'class' => '',
                        'name' => 'plan[options][payment_gateway]',
                        'label' => trans('messages.plan.choose_a_payment_gateway'),
                        'value' => $plan->getOption('payment_gateway'),
                        'help_class' => 'plan',
                        'options' => \Acelle\Model\Setting::getActivePaymentGatewaySelectOptions(),
                        'help' => trans('messages.plan.choose_a_payment_gateway.help'),
                        'rules' => '',
                    ])
                    
                    <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
                    <a href="{{ action('Admin\PlanController@index') }}" type="button" class="btn btn-mc_inline">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
        
@endsection
