@extends('layouts.core.login')

@section('title', trans('messages.2fa.email_verification'))

@section('content')
    
    <!-- send reset password email -->
    <form class="" role="form" method="POST" action="{{ action('UserController@emailVerify') }}">
        {{ csrf_field() }}
        
        <div class="panel panel-body p-4 rounded-3 bg-white shadow">                        
            
            @if (session('status'))
                <div class="alert alert-success">
        {{ session('status') }}
                </div>
            @endif
            
            <h4 class="text-semibold mt-0">{{ trans('messages.password_reset') }}</h4>
            <p>{{ trans('messages.2fa.email_verification.email_sent') }}</p>
            
            <div class="form-group has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="text" class="form-control" name="email"
                    placeholder="{{ trans("messages.2fa.email_verification.enter_code_here") }}"
                    value=""
                />
                <div class="form-control-feedback has-label">
        <i class="icon-envelop5 text-muted"></i>
                </div>
                @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
                @endif                            
            </div>
            
            <button type="submit" class="btn btn-secondary">
                {{ trans('messages.2fa.email_verification.verify') }}
            </button>
        </div>
    </form>
    <!-- /send reset password email -->                
    
@endsection



