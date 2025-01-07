@if ($webhookLogs->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($webhookLogs as $key => $webhookLog)
            <tr class="position-relative">
                <td width="1%" class="list-check-col">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $webhookLog->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <label class="m-0 d-block">
                        {{ Auth::user()->admin->formatDateTime($webhookLog->created_at, 'datetime_full') }}
                    </label>
                    <span class="text-muted2">{{ trans('messages.webhook.run_at') }}</span>
                </td>
                <td>
                    <label class="m-0 d-block">
                        {{ $webhookLog->response_http_code }}
                    </label>
                    <span class="text-muted2">{{ trans('messages.webhook.http_code') }}</span>
                </td>
                <td>
                    <label class="m-0 d-block clamp-3" style="max-width: 250px;">
                        {{ $webhookLog->request_details }}
                    </label>
                    <span class="text-muted2">{{ trans('messages.webhook.request_details') }}</span>
                </td>
                <td>
                    <label class="m-0 d-block clamp-3" style="max-width: 250px;">
                        {{ $webhookLog->response_content }}
                    </label>
                    <span class="text-muted2">{{ trans('messages.webhook.reponse_content') }}</span>
                </td>
                <td>
                    <label class="m-0 d-block clamp-3" style="max-width: 250px;">
                        {{ $webhookLog->response_error ?? trans('messages.general.n_a') }}
                    </label>
                    <span class="text-muted2">{{ trans('messages.webhook.reponse_error') }}</span>
                </td>
                <td>
                    <a href="{{ action('Admin\WebhookController@logsDetails', ["id" => $webhookLog->uid]) }}" role="button" class="btn btn-light btn-icon">
                        <span class="material-symbols-rounded">zoom_in</span>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', [
        'items' => $webhookLogs,
    ])
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <span class="material-symbols-rounded">dns</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">dns</span>
        <span class="line-1">
            {{ trans('messages.webhook.empty') }}
        </span>
    </div>
@endif
