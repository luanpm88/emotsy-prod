@if ($subscribers->count() > 0)
    <div class="mb-4 copy-web-url-container">
        @foreach ($subscribers as $key => $subscriber)
            <div class=" d-flex py-4 border-bottom">
                <div class="me-4">
                    <img width="80px" height="80px"
                        class="rounded-circle border shadow-sm"
                        src="{{ (isSiteDemo() ? 'https://i.pravatar.cc/300?v=' . $key : action('SubscriberController@avatar',  $subscriber->uid)) }}"
                    />
                </div>
                <div class="">
                    <div>
                        <h4 class="mb-1 fw-600">{{ $subscriber->getFullName() }}</h4>
                        <p class="mb-3">
                            <a href="mailto:{{ $subscriber->email }}">
                                {{ $subscriber->email }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <div class="mb-1">{{ trans('messages.campaign.preview_as.web_view_url') }}:</div>
                        <p class="mb-0">
                            <a target="_blank" href="https://acellemail.com/features.php">
                                https://acellemail.com/features.php
                                <i class="material-icons-outlined">open_in_new</i>
                            </a>
                            <a href="javascript:;" class="btn btn-default btn-sm ms-3 copy-web-view-url"
                                data-url="https://acellemail.com/features.php"
                            >
                                <i class="material-icons-outlined me-1">copy</i>{{ trans('messages.copy') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @include('elements/_per_page_select', ["items" => $subscribers])

    <script>
        var CampaignPreviewAsList = {
            copy: function(url) {
                copyToClipboard(url, $('.copy-web-url-container'));

                notify({
                    type: 'success',
                    message: '{{ trans('messages.preview_as.url.copied') }}'
                });
            }
        }

        $(function() {
            $('.copy-web-view-url').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('data-url');
                
                CampaignPreviewAsList.copy(url);
            });
        });
    </script>
    
@elseif (!empty(request()->keyword) || !empty(request()->filters))
    <div class="empty-list">
        <span class="material-icons-outlined">
people
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-icons-outlined">
people
</span>
        <span class="line-1">
            {{ trans('messages.subscriber_empty_line_1') }}
        </span>
    </div>
@endif
