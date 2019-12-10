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
                {{ trans('messages.automation.email.setup') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
                @if (isset($email->id))                    
                    onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}')"
                @endif
            >
                <i class="lnr lnr-layers mr-2"></i>
                {{ trans('messages.automation.email.content') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
                @if (isset($email->id))                    
                    onclick="popup.load('{{ action('Automation2Controller@emailConfirm', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}')"
                @endif
            >
                <i class="lnr lnr-cog mr-2"></i>
                {{ trans('messages.automation.email.confirm') }}
            </a>
        </li>
    </ul>
        
    <div class="sub-section">
        <h3 class="mb-4">{{ trans('messages.campaign.email_content') }}</h3>                
        <p>{{ trans('messages.campaign.email_content.intro') }}</p>
            
        <div class="media-left">
            <div class="media">
                <i class="lnr lnr-book" style="
                    font-size: 60px;
                    color: #555;
                "></i>
            </div>
            <div class="main">
                <label>{{ trans('messages.campaign.html_email') }}</label>
                <p>{{ trans('messages.campaign.html_email.last_edit', [
                    'date' => Acelle\Library\Tool::formatDateTime($email->updated_at),
                ]) }}</p>
                
                <a href="{{ action('Automation2Controller@templateEdit', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}" class="btn btn-secondary mr-1 template-compose"
                >
                    {{ trans('messages.campaign.compose_email') }}
                </a>
                <a href="{{ action('Automation2Controller@templateCreate', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}" class="btn btn-outline-secondary template-change"
                >
                    {{ trans('messages.campaign.change_template') }}
                </a>
            </div>
        </div>
    </div>
        
    <div class="sub-section">   
        <h3 class="mt-5">{{ trans('messages.campaign.attachment') }}</h3>
        <p>{{ trans('messages.campaign.attachment.intro') }}</p>
            
        @include('automation2.email._attachment')
    </div>
    
    <script>
        var builder;
    
        $('.template-compose').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');
        
            var div = $('<div class="full-iframe-popup">').html('<iframe scrolling="no" class="builder" src="'+url+'"></iframe>');
            
            $('body').append(div);
        });
        
        $('.template-change').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');
        
            popup.load(url);
        });
    </script>
@endsection