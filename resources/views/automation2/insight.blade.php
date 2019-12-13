@include('automation2._info')
				
@include('automation2._tabs', ['tab' => 'insight'])
    
<div class="insight-topine flex small">
    <div class="insight-desc mr-auto">
        {{ trans('messages.automation.your_overview') }}
    </div>
    <div class="insight-time">
        <i class="lnr lnr-clock"></i> {{ trans('messages.automation.started_at', ['time' => $automation->getStartedTime()]) }}
    </div>
</div>
    
<div class="insight-stat-brief d-flex mt-3 mb-5">
    <div class="insight-stat-col flex-fill">
        <number>{{ format_number($automation->getContactsCount()) }}</number>
        <desc class="text-muted">{{ trans_choice('messages.automation.contacts', $automation->getContactsCount()) }}</desc>
    </div>
    <div class="insight-stat-col flex-fill">
        <number>{{ $automation->getCompletePercent() }}%</number>
        <desc class="text-muted">{{ trans('messages.automation.complete') }}</desc>
    </div>
    <div class="insight-stat-col flex-fill">
        <number>{{ format_number($automation->getEmailsSentCount()) }}</number>
        <desc class="text-muted">{{ trans_choice('messages.automation.emails_sent', $automation->getEmailsSentCount()) }}</desc>
    </div>
</div>
    
<p class="insight-intro">
    {{ trans('messages.automation.insight.intro') }}
</p>
    
<div class="mc-table small mt-4">
    <div class="mc-row d-flex align-items-center">
        <div class="media trigger">
            <a href="">
                <i class="lnr lnr-exit-up bg-danger"></i>
            </a>
        </div>
        <div class="flex-fill">
            <label class="font-weight-semibold">
                Triggered
            </label>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill">
            <label class="font-weight-semibold">
                2 days ago
            </label>
            <desc>Lasted updated</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                100%
            </label>
        </div>
    </div>
    <div class="mc-row d-flex align-items-center">
        <div class="media email">
            <a href="">
                <i class="lnr lnr-envelope bg-primary"></i>
            </a>
        </div>
        <div class="flex-fill">
            <label class="font-weight-semibold">
                Triggered
            </label>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill">
            <label class="font-weight-semibold">
                2 days ago
            </label>
            <desc>Lasted updated</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                100%
            </label>
        </div>
    </div>
    <div class="mc-row d-flex align-items-center">
        <div class="media open">
            <a href="">
                <i class="lnr lnr-exit-up bg-info"></i>
            </a>
        </div>
        <div class="flex-fill">
            <label class="font-weight-semibold">
                Triggered
            </label>
            <desc>503 contacts</desc>
        </div>
        <div class="flex-fill">
            <label class="font-weight-semibold">
                2 days ago
            </label>
            <desc>Lasted updated</desc>
        </div>
        <div class="flex-fill text-center">
            <label class="font-weight-semibold">
                100%
            </label>
        </div>
    </div>
</div>