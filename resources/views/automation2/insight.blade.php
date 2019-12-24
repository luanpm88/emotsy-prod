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
    
<div class="insight-stat-brief d-flex mt-3 mb-4">
    <div class="insight-stat-col flex-fill">
        <number>{{ format_number($stats['total']) }}</number>
        <desc class="text-muted">{{ trans_choice('messages.automation.contacts', $automation->getContactsCount()) }}</desc>
    </div>
    <div class="insight-stat-col flex-fill">
        <number>{{ format_number($stats['involed']) }}</number>
        <desc class="text-muted">{{ trans('messages.automation.involved') }}</desc>
    </div>
    <div class="insight-stat-col flex-fill">
        <number>{{ calc_percentage($stats['complete'], $stats['involed']) }}</number>
        <desc class="text-muted">{{ trans('messages.automation.complete_percent') }}</desc>
    </div>
</div>
    
<p class="insight-intro">
    {{ trans('messages.automation.insight.intro') }}
</p>
    
<div class="mc-table small mt-3">
    @foreach ($insight as $key => $element)
        @php
            $action = $automation->getElement($key);
        @endphp

        <div class="mc-row d-flex align-items-center">
            <div class="media trigger">
                {!! $action->getIcon() !!}
            </div>
            <div class="flex-fill" style="width: 35%">
                <label class="font-weight-semibold">
                    {{ $action->getName() }}
                </label>
                <desc>
                    {{ trans_choice('messages.automation.action.contacts_count', $element['percentage'], [
                        'count' => format_number($element['percentage']),
                    ]) }}
                </desc>
            </div>
            <div class="flex-fill">
                <label class="font-weight-semibold">
                    {{ \Carbon\Carbon::parse($element['latest_activity'])->diffForHumans() }}
                </label>
                <desc>{{ trans('messages.automation.action.last_updated') }}</desc>
            </div>
            <div class="flex-fill text-center">
                <label class="font-weight-semibold">
                    {{ format_number($element['percentage']) }}%
                </label>
            </div>
        </div>
            
    @endforeach
</div>