@extends('layouts.backend')

@section('title', $plan->name)

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
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
            <div class="col-md-7">
                <div class="mc_section">
                    <h2>{{ trans('messages.plan.email_footer') }}</h2>
                        
                    <p>{{ trans('messages.plan.email_footer.intro') }}</p>
                        
                    <div class="form-group-checkboxes">                        
                        @include('helpers.form_control', [
                            'class' => '',
                            'type' => 'checkbox2',
                            'name' => 'plan[options][email_footer_enabled]',
                            'label' => trans('messages.plan.enabled_email_footer'),
                            'value' => $plan->getOption('email_footer_enabled'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->validationRules()['options'],
                        ])
                        
                        @include('helpers.form_control', [
                            'class' => '',
                            'type' => 'checkbox2',
                            'name' => 'plan[options][email_footer_trial_period_only]',
                            'label' => trans('messages.plan.email_footer_trial_period_only'),
                            'value' => $plan->getOption('email_footer_trial_period_only'),
                            'options' => ['no','yes'],
                            'help_class' => 'plan',
                            'rules' => $plan->validationRules()['options'],
                        ])
                    </div>
                    
                    @include('helpers.form_control', [
                        'class' => 'builder-editor',
                        'type' => 'textarea',
                        'name' => 'plan[options][html_footer]',
                        'label' => trans('messages.plan.html_footer'),
                        'value' => $plan->getOption('html_footer'),
                        'help_class' => 'plan',
                        'rules' => $plan->validationRules()['options'],
                    ])
                    
                    @include('helpers.form_control', [
                        'class' => '',
                        'type' => 'textarea',
                        'name' => 'plan[options][plain_text_footer]',
                        'label' => trans('messages.plan.plain_text_footer'),
                        'value' => $plan->getOption('plain_text_footer'),
                        'help_class' => 'plan',
                        'rules' => $plan->validationRules()['options'],
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
