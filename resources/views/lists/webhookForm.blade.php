@extends('layouts.popup.large')

@section('content')
    <h2>{{ trans('messages.list.webhook_integration') }}</h2>
    <p>{{ trans('messages.list.webhook.intro') }}</p>

    <ul class="nav nav-tabs nav-tabs-top nav-underline">
        <li class="nav-item">
            <a webhook-control="tab-link" class="nav-link" href="{{ action('MailListController@webhookJson', [
                'uid' => $list->uid,
            ]) }}">
                <span class="material-symbols-rounded">data_object</span>
                {{ trans('messages.list.webhook.json_post') }}
            </a>
        </li>
        <li class="nav-item">
            <a webhook-control="tab-link" class="nav-link active" href="{{ action('MailListController@webhookForm', [
                'uid' => $list->uid,
            ]) }}">
                <span class="material-symbols-rounded">select_window</span>  {{ trans('messages.list.webhook.form_post') }}
            </a>
        </li>
    </ul>

    <div id="WebhookPopup">
        <div class="row">
            <div class="col-md-8">
                <p>{{ trans('messages.list.webhook.form.intro') }}</p>
                <div>
                    <div class="d-flex">
                        <div class="me-2 w-100">
                            <input type="text" class="form-control disabled" disabled name="" value="{{ action('SubscriberController@store', [
                                'list_uid' => $list->uid,
                            ]) }}" />
                        </div>
                        <div>
                            <button webhook-control="copy-action" class="btn btn-secondary">{{ trans('messages.copy') }}</button>
                        </div>
                    </div>
                </div>

                <p class="mt-4">{{ trans('messages.list.webhook.curl_example') }}</p>

                <div class="border">
                    <div class="bg-light py-2 px-3 border-bottom">
                        {{ trans('messages.list.webhook.posting_json_with_curl') }}Posting JSON with Curl
                    </div>
                    <div class="bg-light p-2 px-3">
                        <pre class="mb-0"><code class="language-curl">curl -X POST https://reqbin.com/echo/post/json
    -H 'Content-Type: application/json'
    -d "{"login": "my_login", "password" : "my_password"}'</code></pre>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <p>{{ trans('messages.list.webhook.fields_available_sample') }}</p>

                <div>
                    @foreach ($list->fields as $field)
                        <a href="javascript:;" class="btn btn-light rounded-pill px-3 me-1">{{ $field->label }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    

    <script>
        $(function() {
            document.querySelectorAll('pre code').forEach((el) => {
                hljs.highlightElement(el);
            });

            // copy action
            $('[webhook-control="copy-action"]').on('click', function(e) {
                copyToClipboard('{{ action('SubscriberController@store', [
                    'list_uid' => $list->uid,
                ]) }}', $('#WebhookPopup'));

                //
                notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.list.webhook.action.copied') }}');
            });

            // tab links
            $('[webhook-control="tab-link"]').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                listWebhook.popup.load(url);
            });
        });
    </script>
@endsection