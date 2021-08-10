@extends('layouts.core.backend')

@section('title', $plan->name)

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PlanController@index") }}">{{ trans('messages.plans') }}</a></li>
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
                    <h2>{{ trans('messages.plan.email_verification') }}</h2>
                        
                    <p>{{ trans('messages.plan.email_verification.intro') }}</p>
                        
                    <div class="form-group control-radio">
                        
                        <div class="radio_box" data-popup='tooltip' title="">
                            <label class="main-control">
                                <input {{ ($plan->getOption('create_email_verification_servers') == 'no' ? 'checked' : '') }} type="radio"
                                    name="plan[options][create_email_verification_servers]"
                                    value="no" class="styled" />
                                        <rtitle>{{ trans('messages.plan.email_verification.use_system') }}</rtitle>
                                <div class="desc text-normal mb-10">
                                    {{ trans('messages.plan.email_verification.use_system.intro') }}
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
                                                'value' => $plan->getOption('verification_credits_limit'),
                                                'label' => trans('messages.verification_credits_limit'),
                                                'help_class' => 'plan',
                                                'options' => ['true', 'false'],
                                                'rules' => $plan->validationRules()['options'],
                                            ])
                                        </div>
                                        <div class="col-md-4 pt-4">
                                            <div class="checkbox inline text-semibold">
                                                <label>
                                                    <input{{ $plan->getOption('email_verification_servers_max')  == -1 ? " checked=checked" : "" }} type="checkbox" class="unlimit-check styled">
                                                    {{ trans('messages.unlimited') }}
                                                </label>
                                            </div>
                                        </div>
                                            
                                            
                                    </div>
                                </div>
                                <hr>
                                @include('helpers.form_control', ['type' => 'checkbox2',
                                    'class' => '',
                                    'name' => 'plan[options][all_email_verification_servers]',
                                    'value' => $plan->getOption('all_email_verification_servers'),
                                    'label' => trans('messages.use_all_email_verification_servers'),
                                    'options' => ['no','yes'],
                                    'help_class' => 'plan',
                                    'rules' => $plan->validationRules()['options'],
                                ])
                                @if(!Acelle\Model\EmailVerificationServer::getAllAdminActive()->count())
                                    <div class="empty-list">
                                        <i class="icon-database-check"></i>
                                        <span class="line-1">
                                            {{ trans('messages.email_verification_server_no_active') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="email-verification-servers">
                                        
                                        <p class="mb-0">{{ trans('messages.plan.email_verification.select_server') }}</p>
                                        <div class="">                                        
                                            @foreach (Acelle\Model\EmailVerificationServer::getAllAdminActive()->orderBy("name")->get() as $server)
                                                <div class="">
                                                    <div class="">
                                                        <div class="form-group-mb-0 mt-2">
                                                            @include('helpers.form_control', [
                                                                'type' => 'checkbox2',
                                                                'name' => 'plan[email_verification_servers][' . $server->uid . '][check]',
                                                                'value' => $plan->plansEmailVerificationServers->contains('server_id', $server->id),
                                                                'label' => $server->name,
                                                                'options' => [false, true],
                                                                'help_class' => 'plan',
                                                                'rules' => $plan->validationRules()['options'],
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>                        
                    </div>
                </div>
                <button class="btn btn-secondary mr-10">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\PlanController@index') }}" role="button" class="btn btn-link">
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
            

            // select servers check
            var manager2 = new GroupManager();
    
            manager2.add({
                checkBox: $("input[name='plan[options][all_email_verification_servers]']"),
                serversBox: $(".email-verification-servers")
            });
    
            manager2.bind(function(group) {
                var doCheck = function() {
                    var checked = group.checkBox.is(':checked');
                    
                    if (!checked) {
                        group.serversBox.show();
                    } else {
                        group.serversBox.hide();
                    }
                };
    
                group.checkBox.on('change', function() {
                    doCheck();
                });
    
                doCheck();
            });
        });
    </script>
        
@endsection
