@extends('layouts.popup.medium')

@section('content')
    <p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
        
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')">
                <i class="lnr lnr-cog mr-2"></i>
                Setup
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
                @if (isset($email->id))                    
                    onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}')"
                @endif
            >
                <i class="lnr lnr-layers mr-2"></i>
                Template
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;">
                <i class="lnr lnr-cog mr-2"></i>
                Confirm
            </a>
        </li>
    </ul>
        
    <h3 class="mb-4">Email Setup</h3>    
    <p>Please fill-up email information below. They will be used to apply to all emails that send to customers.</p>
    
    <form id="emailSetup" action="{{ action('Automation2Controller@emailSetup', $automation->uid) }}" method="POST">
        {{ csrf_field() }}
    
        <ul class="key-value-list">
            <li>
                <label>
                    2 recepients
                </label>
                <div class="value">
                    Sample Mail List
                </div>
            </li>
            <li>
                <label>
                    Subject
                </label>
                <div class="value">
                    Happy Birthday!
                </div>
            </li>
            <li>
                <label>
                    Tracking
                </label>
                <div class="value">
                    Opens, Clicks
                </div>
            </li>
        </ul>    
            
        <button class="btn btn-secondary mt-4">{{ trans('messages.email.setup.save_next') }}</button>
    </form>
    
    <script>
    </script>
@endsection