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
        'menu' => 'email_verification',
    ])
    
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanGroupController@save', $planGroup->uid) }}" method="POST" class="form-validate-jqueryx">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-6">
                <div class="mc_section">
                    <h2>{{ trans('messages.plan_group.email_verification') }}</h2>
                        
                    <p>{{ trans('messages.plan_group.email_verification.intro') }}</p>
                        
                    <div class="form-group control-radio">
                        <div class="radio_box" data-popup='tooltip' title="">
                            <label class="main-control">
                                <input {{ ($planGroup->getOption('create_email_verification_servers') == 'yes' ? 'checked' : '') }} type="radio"
                                    name="plan[options][create_email_verification_servers]"
                                    value="yes" class="styled" />
                                    <rtitle>{{ trans('messages.plan_group.email_verification.use_own') }}</rtitle>
                                <div class="desc text-normal mb-10">
                                    {{ trans('messages.plan_group.email_verification.use_own.intro') }}
                                </div>
                            </label>
                        </div>
                        <hr>
                        <div class="radio_box" data-popup='tooltip' title="">
                            <label class="main-control">
                                <input {{ ($planGroup->getOption('create_email_verification_servers') == 'no' ? 'checked' : '') }} type="radio"
                                    name="plan[options][create_email_verification_servers]"
                                    value="no" class="styled" />
                                        <rtitle>{{ trans('messages.plan_group.email_verification.use_system') }}</rtitle>
                                <div class="desc text-normal mb-10">
                                    {{ trans('messages.plan_group.email_verification.use_system.intro') }}
                                </div>
                            </label>
                            <div class="radio_more_box">
                                <div class="boxing">
                                    <div class="row">
                                        <div class="col-md-8">
                                            @include('helpers.form_control', [
                                                'type' => 'text',
                                                'class' => 'numeric',
                                                'name' => 'plan[options][verification_credits_limit]',
                                                'value' => $planGroup->getOption('verification_credits_limit'),
                                                'label' => trans('messages.verification_credits_limit'),
                                                'help_class' => 'plan',
                                                'options' => ['true', 'false'],
                                                'rules' => $planGroup->validationRules()['options'],
                                            ])
                                        </div>
                                        <div class="col-md-4 pt-4">
                                            <div class="checkbox inline text-semibold">
                                                <label>
                                                    <input{{ $planGroup->getOption('verification_credits_limit')  == -1 ? " checked=checked" : "" }} type="checkbox" class="unlimit-check styled">
                                                    <span class="ms-1">{{ trans('messages.unlimited') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\PlanGroupController@index') }}" role="button" class="btn btn-link">
                    {{ trans('messages.cancel') }}
                </a>
            </div>
        </div>
    </form>

    <script>
        $(function() {
            var manager = new GroupManager();
    
            $('.boxing').each(function() {
                manager.add({
                    textBox: $(this).find('input[type=text]'),
                    unlimitedCheck: $(this).find('.unlimit-check'),
                    defaultValue: '10000',
                    currentValue: $(this).find('input[type=text]').val()
                });
            });
    
            manager.bind(function(group) {
                var doCheck = function() {
                    var checked = group.unlimitedCheck.is(':checked');
                    
                    if (checked) {
                        group.currentValue = group.textBox.val();
                        group.textBox.val(-1);
                        group.textBox.addClass("text-trans");
                        group.textBox.attr("readonly", "readonly");
                    } else {
                        if(group.textBox.val() == "-1") {
                            if (group.currentValue != "-1") {
                                group.textBox.val(group.currentValue);
                            } else {
                                group.textBox.val(group.defaultValue);
                            }
                        }
                        group.textBox.removeClass("text-trans");
                        group.textBox.removeAttr("readonly", "readonly");
                    }
                };
    
                group.unlimitedCheck.on('change', function() {
                    doCheck();
                });
    
                doCheck();
            });
        });
    </script>
        
@endsection
