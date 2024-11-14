<div class="mb-4">
    <div>
        <h4 class="fw-semibold">{{ trans('messages.automation.outgoing_webhook.test_request') }}</h4>
        <div class="p-3 bg-light border">
            <pre>{{ json_encode(request()->all(), JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</div>

<div class="">
    <div>
        <h4 class="fw-semibold">{{ trans('messages.automation.outgoing_webhook.test_output') }}</h4>
        <div class="p-3 bg-light border">
            <div test-control="output">
                <div class="my-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <div id="webhook-loading"></div>
                    </div>
                    <div class="text-center mt-4 fs-5">
                        {{ trans('messages.automation.outgoing_webhook.testing_webhook') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>