@extends('layouts.popup.small')

@section('content')
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:100);

        #webhook-loading {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 3px solid #007c99;
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            -webkit-animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }
    </style>

    <div class="my-4">
        <div class="d-flex align-items-center justify-content-center">
            <div id="webhook-loading"></div>
        </div>
        <div class="text-center mt-4 fs-5">
            {{ trans('messages.automation.outgoing_webhook.testing_webhook') }}
        </div>
    </div>

    <div class="d-none">
        <div>
            <h2>{{ trans('messages.automation.outgoing_webhook.test_output') }}</h2>
            <div class="p-3 bg-light border">

            </div>
        </div>
    </div>

    <script>
        $(() => {

        });

        var WebhookTest = class {
            constructor(options) {
                this.container = options.container;
                this.url = options.url;

                this.test();
            }

            test() {
                $.ajax({
                    url: this.url,
                    type: $(this).prop('method'),
                    dataType: 'html',
                    data: $(this).serialize(),
                }).done(function(response) {
                    SendingLimit.getSendingLimitPopup().hide();
                    $('.sendind-limit-select-custom').html(response);                    
                    initJs($('.sendind-limit-select-custom'));                    
                });
            }
        }
    </script>
@endsection
