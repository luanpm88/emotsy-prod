<h4 class="text-semibold text-primary mb-0">{{ trans('messages.features') }}</h4>
<ul class="mt-0 mb-0 top-border-none plans-intro">
    <li>
        {!! trans('messages.sending_total_quota_intro', ["value" => $planGroup->displayTotalQuota()]) !!}
    </li>
    <li>
        {!! trans('messages.sending_quota_intro', ["value" => $planGroup->displayQuota()]) !!}
    </li>
    <li>
        {!! trans('messages.max_lists_intro', ["value" => $planGroup->displayMaxList()]) !!}
    </li>
    <li>
        {!! trans('messages.max_subscribers_intro', ["value" => $planGroup->displayMaxSubscriber()]) !!}
    </li>
    <li>
        {!! trans('messages.max_campaigns_intro', ["value" => $planGroup->displayMaxCampaign()]) !!}
    </li>
    <li>
        {!! trans('messages.max_size_upload_total_intro', ["value" => $planGroup->displayMaxSizeUploadTotal()]) !!}
    </li>
    <li>
        {!! trans('messages.max_file_size_upload_intro', ["value" => $planGroup->displayFileSizeUpload()]) !!}
    </li>
    <li>
        {!! trans('messages.allow_create_sending_servers_intro', ["value" => $planGroup->displayAllowCreateSendingServer()]) !!}
    </li>
    <li>
        {!! trans('messages.allow_create_sending_domains_intro', ["value" => $planGroup->displayAllowCreateSendingDomain()]) !!}
    </li>
    <li>
        {!! trans('messages.allow_create_email_verification_servers_intro', ["value" => $planGroup->displayAllowCreateEmailVerificationServer()]) !!}
    </li>
</ul>
