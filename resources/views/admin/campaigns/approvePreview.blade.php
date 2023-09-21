@extends('layouts.popup.full')

@section('title')
    {{ trans('messages.campaign.preview_approve') }}
@endsection

@section('content')
    <style>
        .email_preview_frame {
            width: 100%;
            height: calc(100vh - 180px);
            border-radius: 5px;
            box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.05);
            transition: width 0.2s ease-in-out;
        }
        .mode-mobile .email_preview_frame {
            width: 550px;
        }
        .btn-mode {
            background-color: transparent!important;
            border: none;
            border-radius: 3px!important;
        }
        .btn-mode:hover {
            background-color: rgba(184, 184, 184, 0)!important;
            box-shadow: none!important;
        }
        .mode-desktop .btn-mode[data-mode="mode-desktop"], .mode-mobile .btn-mode[data-mode="mode-mobile"] {
            pointer-events: none;
            background-color: #fff!important;
        }
        body.mode-dark .btn-mode {
            background-color: transparent!important;
            border: none;
            border-radius: 3px!important;
        }
        body.mode-dark .btn-mode:hover {
            box-shadow: none!important;
            background-color: rgba(184, 184, 184, 0)!important;
        }
        body.mode-dark .mode-desktop .btn-mode[data-mode="mode-desktop"], body.mode-dark .mode-mobile .btn-mode[data-mode="mode-mobile"] {
            pointer-events: none;
            background-color: rgba(184, 184, 184, 0.2)!important;
        }
    </style>

    <div id="PreviewContainer" class="preview-container mode-desktop"> 
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <div>
                    <a href="{{ action('Admin\CampaignController@approve', $campaign->uid) }}" list-control="approve"
                        link-method="POST"
                        title="{{ trans('messages.campaign.approve') }}" role="button"
                        class="btn btn-primary btn-icon me-1"
                    >
                        {{ trans('messages.campaign.approve') }}
                    </a>
                    <a href="{{ action('Admin\CampaignController@reject', $campaign->uid) }}" list-control="reject"
                        class="btn btn-warning btn-icon"
                    >
                        {{ trans('messages.campaign.reject') }}
                    </a>
                </div>
                <div class="ms-auto">
                    <div class="d-flex align-items-center">
                        <div class="bg-light mode-group rounded p-1">
                            <div class="btn-group">
                                <a preview-action="mode" data-mode="mode-desktop" href="#" class="btn btn-default btn-mode me-2">
                                    <span class="material-symbols-rounded">computer</span>
                                    {{ trans('messages.campaign.preview.desktop') }}
                                </a>
                                <a preview-action="mode" data-mode="mode-mobile" href="#" class="btn btn-default btn-mode">
                                    <span class="material-symbols-rounded">smartphone</span>
                                    {{ trans('messages.campaign.preview.mobile') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    
        </div>

        <div class="tabcontent">
            <div class="d-flex justify-content-center" style="width:100%;">
                <div class="pe-4">
                    <div class="head">
                        <h2 class="text-semibold mb-4">{{ trans('messages.overview') }}</h2>
                    </div>

                    <ul class="modern-listing">
                        @if (!is_null($score))
        
                            <li class="d-flex align-items-center">
                                <!-- {{ $count = $campaign->subscribersCount() }} -->
        
                                <span class="fs-4 me-4">
                                    @if ($score['result'] == true)
                                        <i class="material-symbols-rounded text-success">task_alt</i>
                                    @else
                                        @if (!Acelle\Model\Setting::isYes('spamassassin.required'))
                                            <span class="material-symbols-rounded text-warning">error_outline</span>
                                        @else
                                            <span class="material-symbols-rounded text-danger">highlight_off</span>
                                        @endif
                                    @endif
                                   
                                </span>
                                <div class="me-auto">
                                    <h4>{{ trans('messages.campaign.spam_score') }}</h4>
                                    <p>
                                        @if ($score['result'] == true)
                                            <i class="label label-flat bg-active">{{ trans('messages.campaign.score.passed') }} {{ $score['score'] }}</i>
                                        @else
                                            <i class="badge badge-warning">{{ trans('messages.campaign.score.failed') }} {{ $score['score'] }}</i>
                                        @endif
                                    </p> 
                                </div>
                            </li>
                        @endif
                        <li class="d-flex align-items-center">
                            
                            <!-- {{ $count = $campaign->subscribersCount() }} -->
        
                            <span class="fs-4 me-4">
                                @if ($count)
                                    <i class="material-symbols-rounded text-success">task_alt</i>
                                @else
                                    <span class="material-symbols-rounded text-danger">highlight_off</span>
                                @endif
                            </span>
                            <div class="me-auto">
                                <h4>{{ number_with_delimiter($count) }} {{ trans('messages.recipients') }}</h4>
                                <p>
                                    {!! $campaign->displayRecipients() !!}
                                </p>
                            </div>
                        </li>
        
                        <li class="d-flex align-items-center">
                            <span class="fs-4 me-4">
                                <i class="material-symbols-rounded text-success">task_alt</i>
                            </span>
                            <div class="me-auto">
                                <h4>{{ trans('messages.email_subject') }}</h4>
                                <p>
                                    {{ $campaign->subject }}
                                </p>    
                            </div>
                        </li>
        
                        <li class="d-flex align-items-center">
                            <span class="fs-4 me-4">
                                <i class="material-symbols-rounded text-success">task_alt</i>
                            </span>
                            <div class="me-auto">
                                <h4>{{ trans('messages.reply_to') }}</h4>
                                <p>
                                    {{ $campaign->reply_to }}
                                </p>   
                            </div>
                        </li>
        
                        <li class="d-flex align-items-center">
                            <span class="fs-4 me-4">
                                <i class="material-symbols-rounded text-success">task_alt</i>
                            </span>
                            <div class="me-auto">
                                <h4>{{ trans('messages.tracking') }}</h4>
                                <p>
                                    @if ($campaign->track_open)
                                        {{ trans('messages.opens') }}<pp>,</pp>
                                    @endif
                                    @if ($campaign->track_click)
                                        {{ trans('messages.clicks') }}<pp>,</pp>
                                    @endif
                                </p>
                            </div>
                        </li>
        
                        <li class="d-flex align-items-center confirm-webhooks-summary">
                            <span class="fs-4 me-4">
                                <i class="material-symbols-rounded text-success">task_alt</i>
                            </span>
                            <div class="me-auto">
                                <h4>{{ number_with_delimiter($campaign->campaignWebhooks()->count()) }} {{ trans('messages.webhooks') }}</h4>
                                
                                <p class="">{{ trans('messages.webhooks.summary_desc') }}</p>
                                <p class="mb-0">{!! implode(' ', array_unique($campaign->campaignWebhooks()->select('type')->get()->map(function($h) {
                                        return '<span class="text-muted2 small badge badge-success mt-2">' . trans('messages.webhook.type.' . $h->type . '.short') . '</span>';
                                    })->toArray())) !!}
                                </p>
                            </div>
                        </li>
        
                        @if (!$campaign->run_at)
                            <li class="d-flex align-items-center">
                                <span class="fs-4 me-4">
                                    <i class="material-symbols-rounded text-success">task_alt</i>
                                </span>
                                <div class="me-auto">
                                    <h4>{{ trans('messages.campaign.run_immediately') }}</h4>
                                    <p>
                                        {{ trans('messages.campaign.click_send_to_proceed') }}
                                    </p>
                                </div>
                            </li>
                        @else
                            <li class="d-flex align-items-center">
                                <span class="fs-4 me-4">
                                    <i class="material-symbols-rounded text-muted2">alarm</i>
                                </span>
                                <div class="me-auto">
                                    <h4>{{ trans('messages.campaign.scheduled_at') }} ({{ $campaign->scheduleDiffForHumans() }})</h4>
                                    <p>
                                        {{ isset($campaign->run_at) ? Auth::user()->admin->formatDateTime($campaign->run_at, 'datetime_full_with_timezone') : "" }}
                                    </p>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="d-flex justify-content-center p-3 bg-light" style="width:100%;">
                    <iframe id="PreviewFrame" class="email_preview_frame" src="{{ action('Admin\CampaignController@previewContent', $campaign->uid) }}"></iframe>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        $(function() {
            PreviewMode.init();

            //
            CampaignReject.init();
        })

        var PreviewMode = function () {
            var buttons;
            var frame;
            var container;

            var handleButtonsClick = function () {
                buttons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        var mode = button.getAttribute('data-mode');
                        
                        changeMode(mode);
                    })
                });
            };

            var changeMode = function (mode) {  
                container.classList.remove('mode-desktop');
                container.classList.remove('mode-mobile');

                container.classList.add(mode);
            };

            return {
                init: function () {
                    buttons = document.querySelectorAll('[preview-action="mode"]');
                    frame = document.getElementById('PreviewFrame');
                    container = document.getElementById('PreviewContainer');

                    // event
                    handleButtonsClick();
                }
            }
        }();

        var CampaignReject = function () {
            var popup;
            var button;

            return {
                init: function () {
                    popup = new Popup();
                    button = document.querySelector('[list-control="reject"]');

                    //
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        var url = $(this).attr('href');
                        popup.load(url);
                    })
                },

                getPopup: function() {
                    return popup;
                }
            }
        }();
    </script>
@endsection