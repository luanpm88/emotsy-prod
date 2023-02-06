<div>
    <table class="table table-bordered box-shadow-sm rounded bg-white">
        <tr>
            <th width="250px" class="bg-light" style="vertical-align:middle;align-items:center">
                {{ trans('messages.plan_name') }}
            </th>

            @foreach($plans as $plan)
                <td>
                    <h3 class="mb-1 font-weight-semibold">{{ $plan->name }}</h3>
                    <p class="mb-2">{{ $plan->description }}</p>
                    <p class="mb-2 font-weight-semibold">
                        {{ \Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}
                        / {!! $plan->displayFrequencyTime() !!}
                    </p>
                    <p class="mb-0 py-3">
                        <a
                            href="{{ action('UserController@register', [
                                'plan_uid' => $plan->uid,
                            ]) }}"
                            class="btn fw-600 btn-primary rounded-3 d-block py-2 shadow-sm">
                                @if ($plan->isFree() || $plan->hasTrial())
                                    {{ trans('messages.plan.select') }}
                                @else
                                    {{ trans('messages.plan.buy') }}
                                @endif
                        </a>
                        @if ($plan->hasTrial())
                            <p
                                href="{{ action('UserController@register', [
                                    'plan_uid' => $plan->uid,
                                ]) }}"
                                class="mt-3 fw-600 mb-0 text-center">
                                    {{ trans('messages.plan.has_trial', [
                                        'time' => $plan->getTrialPeriodTimePhrase(),
                                    ]) }}
                            </p>
                        @endif
                    </p>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.sending_quota_label') }}
            </th>
            @foreach($plans as $plan)
                <td>
                    <span>{{ $plan->displayTotalQuota() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.max_lists_label') }}
            </th>
            @foreach($plans as $plan)
                <td>
                    <span>{{ $plan->displayMaxList() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.max_subscribers_label') }}
            </th>
            @foreach($plans as $plan)
                <td>
                    <span>{{ $plan->displayMaxSubscriber() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.max_campaigns_label') }}
            </th>
            @foreach($plans as $plan)
                <td>
                    <span>{{ $plan->displayMaxCampaign() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.max_size_upload_total_label') }}
            </th>
            @foreach($plans as $plan)
                <td>
                    <span>{{ $plan->displayMaxSizeUploadTotal() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.max_file_size_upload_label') }}
            </th>
            @foreach($plans as $plan)
                <td>
                    <span>{{ $plan->displayFileSizeUpload() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.allow_create_sending_servers_label') }}
            </th>
            @foreach($plans as $plan)
                <td class="{{ ($plan->getOption('sending_server_option') == \Acelle\Model\Plan::SENDING_SERVER_OPTION_OWN ? '' : 'bg-light') }}">
                    <span>{!! $plan->displayAllowCreateSendingServer() !!}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.allow_create_sending_domains_label') }}
            </th>
            @foreach($plans as $plan)
                <td class="{{ $plan->getOption('create_sending_domains') == 'no' ? 'bg-light' : '' }}">
                    <span>{{ $plan->displayAllowCreateSendingDomain() }}</span>
                </td>
            @endforeach
        </tr>
        <tr>
            <th class="fw-normal bg-light">
                {{ trans('messages.allow_create_email_verification_servers_label') }}
            </th>
            @foreach($plans as $plan)
                <td class="{{ $plan->getOption('create_email_verification_servers') == 'no' ? 'bg-light' : '' }}">
                    <span>{!! $plan->displayAllowCreateEmailVerificationServer() !!}</span>
                </td>
            @endforeach
        </tr>
    </table>
</div>  