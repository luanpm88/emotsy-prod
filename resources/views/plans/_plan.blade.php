@if (is_object($plan))
    <div class="panel panel-plan box-{{ $plan->status }}">
        <div class="panel-heading" style="background-color: {{ $plan->color }}; color: white">
            <h4 class="pull-left panel-title text-center">													
                {{ $plan->name }}
            </h4>
        </div>
        <div class="plan-price-box">
            @if ($plan->price == 0.0)
                <span class="price">{{ trans('messages.free') }}</span>
            @else
                <span class="price">{{ Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}</span> / {{ $plan->frequency_amount }} {{ \Acelle\Library\Tool::getPluralPrase($plan->frequency_unit, $plan->frequency_amount)  }}
            @endif
                
        </div>
        <div class="panel-body pt-0">
            <ul class="mt-0 mb-0 top-border-none plans-intro">
                <li>
                    {!! trans('messages.sending_quota_intro', ["value" => $plan->displayQuota()]) !!}
                </li>
                <li>
                    {!! trans('messages.max_lists_intro', ["value" => $plan->displayMaxList()]) !!}
                </li>
                <li>
                    {!! trans('messages.max_subscribers_intro', ["value" => $plan->displayMaxSubscriber()]) !!}
                </li>
                <li>
                    {!! trans('messages.max_campaigns_intro', ["value" => $plan->displayMaxCampaign()]) !!}
                </li>
                <li>
                    {!! trans('messages.max_size_upload_total_intro', ["value" => $plan->displayMaxSizeUploadTotal()]) !!}
                </li>
                <li>
                    {!! trans('messages.max_file_size_upload_intro', ["value" => $plan->displayFileSizeUpload()]) !!}
                </li>
            </ul>
        </div>
        <div class="panel-footer pt-10 pb-10 text-center">												
            <a href="{{ action('SubscriptionController@register', $plan->uid) }}"
                title="{{ trans('messages.edit') }}"
                class="btn bg-teal-800 btn-icon">
                    <i class="icon icon-checkmark4 pr-0 mr-0"></i> {{ trans('messages.register') }}
            </a>
        </div>
    </div>
@endif