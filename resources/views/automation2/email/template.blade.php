@extends('layouts.popup.medium')

@section('content')
        
    @include('automation2.email._tabs', ['tab' => 'template'])
    
    <div class="row">
        <div class="col-md-8">
            <div class="sub-section">
                <h5 class="mb-3">{{ trans('messages.campaign.email_content') }}</h5>                
                <p>{{ trans('messages.campaign.email_content.intro') }}</p>
                    
                <div class="media-left">
                    <div class="media">
                        <i class="lnr lnr-book" style="
                            font-size: 60px;
                            color: #555;
                        "></i>
                    </div>
                    <div class="main">
                        <label class="font-weight-bold">{{ trans('messages.campaign.html_email') }}</label>
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
                <h5 class="mt-5 mb-3">{{ trans('messages.campaign.attachment') }}</h5>
                <p>{{ trans('messages.campaign.attachment.intro') }}</p>
                    
                @include('automation2.email._attachment')
            </div>
                
            <div class="text-right mt-5">
                <a href="javascript:;" onclick="sidebar.load(); popup.hide()" class="btn btn-inline-secondary mr-1">
                    {{ trans('messages.close') }}
                </a>
                <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailConfirm', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid
                ]) }}')" class="btn btn-secondary">
                    <span class="d-flex align-items-center">
                        <span>{{ trans('messages.automation.email.next_confirm') }}</span> <i class="material-icons">keyboard_arrow_right</i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    
    <script>
        var builder;
    
        $('.template-compose').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');

            openBuilder(url);
        });
        
        $('.template-change').click(function(e) {
            e.preventDefault();
            
            var url = $(this).attr('href');
        
            popup.load(url);
        });
    </script>
@endsection