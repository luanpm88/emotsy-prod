@extends('layouts.core.login')

@section('title', trans('messages.2fa.email_verification'))

@section('content')
    
    <!-- send reset password email -->
    <form class="" role="form" method="POST" action="{{ action('UserController@verifySelectMethod') }}">
        {{ csrf_field() }}
        
        <div class="panel panel-body p-4 rounded-3 bg-white shadow">                        
            
            @if (session('status'))
                <div class="alert alert-success">
        {{ session('status') }}
                </div>
            @endif
            
            <h4 class="text-semibold mt-0">{{ trans('messages.2fa.email_verification') }}</h4>
            <p>{{ trans('messages.2fa.select.wording') }}</p>
            
            <button type="submit" class="btn btn-primary">
                {{ trans('messages.2fa.email.send_email') }}
            </button>
        </div>
    </form>
    <!-- /send reset password email -->                
    
@endsection