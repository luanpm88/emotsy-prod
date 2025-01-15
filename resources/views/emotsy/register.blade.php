@extends('emotsy.layouts.login')

@section('title', trans('messages.login'))

@section('content')
<style>
    .small {
        font-size: 0.86rem;
    } 
</style>
<!-- Advanced login -->
<form enctype="multipart/form-data" action="{{ action('UserController@register') }}" method="POST" class="register-form">
    {{ csrf_field() }}

    <div class="panel panel-body p-4 rounded-3 bg-white">

        <h4 class="text-semibold mt-0 mb-3 fw-600 fs-5">{{ trans('messages.register') }}</h4>
        <div class="login-description mb-4">{{ trans('messages.login.description') }}</div>

        
    
        <input type="hidden" name="plan_uid" value="{{ request()->plan_uid }}" />

        <div class="form-group control-text mt-4"> 
            <label class="form-label required">{{ trans('messages.account.name') }} <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ $customer->name }}"
                class="form-control form-control-sm rd-10 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                required
            >
            @if ($errors->has('name')) 
                <div class="invalid-feedback"> {{ $errors->first('name') }} </div>
            @endif
        </div>
            
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'email',
            'value' => $user->email,
            'help_class' => 'profile',
            'rules' => $user->registerRules()
        ])

        @if (get_localization_config('show_last_name_first', config('app.locale')))

            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'last_name',
                'value' => $user->last_name,
                'rules' => $user->registerRules()
            ])
            
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'first_name',
                'value' => $user->first_name,
                'rules' => $user->registerRules()
            ])
            
            
        @else 
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'first_name',
                'value' => $user->first_name,
                'rules' => $user->registerRules()
            ])
            
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'last_name',
                'value' => $user->last_name,
                'rules' => $user->registerRules()
            ])
        @endif
        
            
        
        @include('helpers.form_control', [
            'type' => 'password',
            'label'=> trans('messages.new_password'),
            'name' => 'password',
            'rules' => $user->registerRules(),
            'eye' => true,
        ])
        
        @if (config('custom.japan'))
            <input type="hidden" name="timezone" value="Asia/Tokyo" />
        @else
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'timezone',
                'value' => $customer->timezone ?? config('app.timezone'),
                'options' => Tool::getTimezoneSelectOptions(),
                'include_blank' => trans('messages.choose'),
                'rules' => $user->registerRules()
            ])	
        @endif				
        
        @include('helpers.form_control', [
            'type' => 'select',
            'name' => 'language_id',
            'label' => trans('messages.language'),
            'value' => $customer->language_id ?? \Acelle\Model\Language::getDefaultLanguage()->id,
            'options' => Acelle\Model\Language::getSelectOptions(),
            'include_blank' => trans('messages.choose'),
            'rules' => $user->registerRules()
        ])

        <div class="mb-4 mt-4">
            <label>
                <span class="me-2">
                    <input type="checkbox" name="agree_policy_term" value="yes" class="styled">
                </span>
                <span class="checker-symbol"></span>
            </label>
            {!! trans('messages.agree_term_policy', [
                'term_link' => '',
                'policy_link' => '',
            ]) !!}
        </div>
        
        @if (Acelle\Model\Setting::get('registration_recaptcha') == 'yes')
            <div class="d-flex justify-content-center">
                <div class="">
                    <!-- hCaptcha -->
                    @if (\Acelle\Model\Setting::getCaptchaProvider() == 'hcaptcha')
                        @php
                            $hcaptcha = \Acelle\Hcaptcha\Client::initialize();
                        @endphp
                        {!! $hcaptcha->renderFormHtml($errors) !!}
                    @else
                        {!! Acelle\Library\Tool::showReCaptcha($errors) !!}
                    @endif
                </div>
            </div>
        @endif

        <button type="submit" class="btn rounded-2 btn-primary d-block register-button py-2 fw-600 fs-15px"  style="width:100%;" >
            <span class="me-2">{{ trans("messages.register") }}</span>  
        </button>

        <div class="mt-4 no-account mb-5">
            {!! trans('messages.register.already_have_account', [
                'login' => url("/login"),
            ]) !!}
        </div>
    
        <script>
            @if (isSiteDemo())
                $('.res-button').click(function(e) {
                    e.preventDefault();
    
                    notify('notice', '{{ trans('messages.notify.notice') }}', '{{ trans('messages.operation_not_allowed_in_demo') }}');
                });
            @endif

            function addButtonLoadingEffect(button) {
                button.addClass('button-loading');
                button.append('<div class="loader"></div>');
            }

            function removeButtonLoadingEffect(button) {
                button.removeClass('button-loading');
                button.find('.loader').remove();
            }

            $(function() {
                $('.register-form').on('submit', function(e) {
                    if (!$('[name="agree_policy_term"]:checked').length) {
                        e.preventDefault();

                        alert('{{ trans('messages.agree_term.required') }}');
                        return false;
                    }

                    //
                    $('.register-button').closest('form').addClass('loading');

                    addButtonLoadingEffect($('.register-button'));
                });
            });
        </script>
        
           
        <div class="mt-4 mb-4 or">
            OR
        </div>


        <div class="socialite d-flex justify-content-center">
            @if (\Acelle\Model\Setting::get('oauth.google_enabled') == 'yes')
                <div class="text-center px-3">
                    <a href="{{ action('AuthController@googleRedirect', [
                        'language_code' => language_code(),
                    ]) }}" class="py-2" > 
                        <span class="display-6 text-center google-connect">{{ trans('messages.continue_with_google') }}</span>
                    </a>
                </div>
            @endif
            @if (\Acelle\Model\Setting::get('oauth.facebook_enabled') == 'yes')
                <div class="text-center px-3">
                    <a href="{{ action('AuthController@facebookRedirect') }}" class="py-2" > 
                        <span class="ms-3 display-6 text-center facebook-connect">{{ trans('messages.continue_with_facebook') }}</span>
                    </a>
                </div>
            @endif
        </div>

        @if (
            \Acelle\Model\Setting::get('oauth.google_enabled') == 'yes' ||
            \Acelle\Model\Setting::get('oauth.facebook_enabled') == 'yes'
        )
            <div class="socialite_buttons d-none">
                @if (\Acelle\Model\Setting::get('oauth.google_enabled') == 'yes')
                    <div class="text-center mt-2">
                        <a href="{{ action('AuthController@googleRedirect', [
                            'language_code' => language_code(),
                        ]) }}" class="btn btn-light d-flex align-items-center rounded-3 py-2" style="width:100%">
                            <img width="25px" src="{{ url('images/google-login.svg') }}" />
                            <span class="ms-3 display-6 text-center" style="font-size:15px;width:80%">{{ trans('messages.continue_with_google') }}</span>
                        </a>
                    </div>
                @endif

                @if (\Acelle\Model\Setting::get('oauth.facebook_enabled') == 'yes')
                    <div class="text-center mt-2">
                        <a href="{{ action('AuthController@facebookRedirect') }}" class="btn btn-light d-flex align-items-center rounded-3 py-2" style="width:100%">
                            <img width="25px" src="{{ url('images/icons/facebook-logo.svg') }}" />
                            <span class="ms-3 display-6 text-center" style="font-size:15px;width:80%">{{ trans('messages.continue_with_facebook') }}</span>
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div> 
</form>
@endsection