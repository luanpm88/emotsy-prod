@extends('layouts.popup.medium')

@section('content')
    <p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
        
    @include('automation2.email._tabs', ['tab' => 'confirm'])
        
    <h3 class="mb-3">{{ trans('messages.automation.email.you_are_set_to_send') }}</h3>    
    <p>{{ trans('messages.automation.email.review_email_intro') }}</p>
    
    <form id="emailSetup" action="{{ action('Automation2Controller@emailSetup', $automation->uid) }}" method="POST">
        {{ csrf_field() }}
        
        <div class="row">
            <div class="col-md-7">
                <ul class="key-value-list mt-4">
                    <li class="d-flex align-items-center">
                        <div class="list-media mr-4">
                            <i class="material-icons-outlined text-success">check</i>
                        </div>
                        <div class="values mr-auto">
                            <label>
                                {{ trans('messages.automation.email.recipients_count', ['count' => $automation->mailList->subscribersCount()]) }}
                            </label>
                            <div class="value">
                                {{ $automation->mailList->name }}
                            </div>
                        </div>
                        <div class="list-action">
                            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}')" class="btn btn-outline-secondary btn-sm">
                                {{ trans('messages.automation.email.setup') }}
                            </a>
                        </div>
                    </li>
                    <li class="d-flex">
                        <div class="list-media mr-4">
                            <i class="material-icons-outlined text-success">check</i>
                        </div>
                        <div class="values mr-auto">
                            <label>
                                {{ trans('messages.automation.email.subject') }}
                            </label>
                            <div class="value">
                                {{ $email->subject }}
                            </div>
                        </div>
                        <div class="list-action">
                            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}')" class="btn btn-outline-secondary btn-sm">
                                {{ trans('messages.automation.email.setup') }}
                            </a>
                        </div>
                    </li>
                    <li class="d-flex">
                        <div class="list-media mr-4">
                            <i class="material-icons-outlined text-success">check</i>
                        </div>
                        <div class="values mr-auto">
                            <label>
                                {{ trans('messages.automation.email.from') }}
                            </label>
                            <div class="value">
                                {{ $email->from }}
                            </div>
                        </div>
                        <div class="list-action">
                            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}')" class="btn btn-outline-secondary btn-sm">
                                {{ trans('messages.automation.email.setup') }}
                            </a>
                        </div>
                    </li>
                    <li class="d-flex">
                        <div class="list-media mr-4">
                            <i class="material-icons-outlined text-success">check</i>
                        </div>
                        <div class="values mr-auto">
                            <label>
                                {{ trans('messages.reply_to') }}
                            </label>
                            <div class="value">
                                @if($email->reply_to)
                                    {{ $email->reply_to }}
                                @else
                                    <span class="text-warning small">
                                        <i class="material-icons-outlined">warning</i>
                                        {{ trans('messages.email.no_reply_to') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="list-action">
                            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}')" class="btn btn-outline-secondary btn-sm">
                                {{ trans('messages.automation.email.setup') }}
                            </a>
                        </div>
                    </li>
                    <li class="d-flex">
                        <div class="list-media mr-4">
                            @if($email->content)
                                <i class="material-icons-outlined text-success">check</i>
                            @else
                                <i class="material-icons-outlined text-danger">close</i>
                            @endif
                        </div>
                        <div class="values mr-auto">
                            <label>
                                {{ trans('messages.automation.email.template') }}
                            </label>
                            <div class="value">
                                @if($email->content)
                                    {{ trans('messages.automation.email.template_selected_click_edit', [
                                        'link' => '',
                                    ]) }}
                                @else
                                    <span class="text-danger small">
                                        <i class="material-icons-outlined">error_outline</i>
                                        {{ trans('messages.email.no_template') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="list-action">
                            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}')" class="btn btn-outline-secondary btn-sm">
                                {{ trans('messages.automation.email.template') }}
                            </a>
                        </div>
                    </li>
                    <li class="d-flex">
                        <div class="list-media mr-4">
                            <i class="material-icons-outlined text-success">check</i>
                        </div>
                        <div class="values mr-auto">
                            <label>
                                {{ trans('messages.automation.email.tracking') }}
                            </label>
                            <div class="value">
                                @if ($email->track_open)
                                    {{ trans('messages.automation.email.opens') }}@if ($email->track_click),@endif
                                @endif
                                @if ($email->track_click)
                                    {{ trans('messages.automation.email.clicks') }}
                                @endif
                            </div>
                        </div>
                        <div class="list-action">
                            <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                                'uid' => $automation->uid,
                                'email_uid' => $email->uid,
                            ]) }}')" class="btn btn-outline-secondary btn-sm">
                                {{ trans('messages.automation.email.setup') }}
                            </a>
                        </div>
                    </li>
                </ul>    
            </div>
        </div>                
            
        <a href="javascript:;" class="btn btn-secondary mt-4" onclick="popup.hide()">{{ trans('messages.close') }}</a>
    </form>
    
    <script>
    </script>
@endsection