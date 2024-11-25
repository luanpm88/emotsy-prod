<div class="mb-4">
    <div>
        <h4 class="fw-semibold">{{ trans('messages.automation.outgoing_webhook.test_request') }}</h4>
        <div class="p-3 bg-light border">
            <pre>{{ json_encode($options, JSON_PRETTY_PRINT) }}</pre>
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
                        <pre style="width: 100%;
height: auto;
white-space: inherit;">{!! json_encode($result, JSON_PRETTY_PRINT) !!}{!! json_encode($result, JSON_PRETTY_PRINT) !!}{!! json_encode($result, JSON_PRETTY_PRINT) !!}{!! json_encode($result, JSON_PRETTY_PRINT) !!}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>