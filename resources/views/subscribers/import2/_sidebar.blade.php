<div class="wizard-sidebar">
    <ul class="wizard-steps">
        <li>
            <a href="{{ action('SubscriberController@import2Wizard', $list->uid) }}" class="w-upload wizard-link">
                <i class="lnr lnr-cloud-upload"></i>
                <span>
                    <label>{{ trans('messages.subscriber.import.upload_csv_file') }}</label>
                    <p>{{ trans('messages.subscriber.import.upload_csv_file.intro') }}</p>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ action('SubscriberController@import2Mapping', $list->uid) }}" class="w-mapping wizard-link">
                <i class="lnr lnr-list"></i>
                <span>
                    <label>{{ trans('messages.subscriber.import.mapping') }}</label>
                    <p>{{ trans('messages.subscriber.import.mapping.intro') }}</p>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ action('SubscriberController@import2Run', $list->uid) }}" class="w-review wizard-link">
                <i class="lnr lnr-checkmark-circle"></i>
                <span>
                    <label>{{ trans('messages.subscriber.import.review_go') }}</label>
                    <p>{{ trans('messages.subscriber.import.review_go.intro') }}</p>
                </span>
            </a>
        </li>
    </ul>
</div>

<script>
    $(".w-{{ $step }}").addClass('current');
</script>