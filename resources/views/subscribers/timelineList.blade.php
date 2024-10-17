<label class="date small font-weight-semibold mb-0 divider">{{ trans('messages.automation.profile_timeline_head') }}</label>    
@if ($timelines->count() > 0)
    @foreach($timelines as $timeline)
        <div class="activity d-flex py-3 px-4">
            <div class="activity-media pr-4 text-center">
                <div class="mb-2">
                    <span class="activity-icon border p-2 bg-light d-inline-block">
                        <span class="material-symbols-rounded">
                            history_toggle_off
                        </span>
                    </span>
                </div>
                <time class="d-block text-center mini mb-0">{{ $timeline->created_at->diffForHumans() }}</time>
                {!! Acelle\Model\AutomationElement::getIconByType($timeline->activity_type) !!}
            </div>
            <div class="small w-100">
                <action class="d-block mb-1">
                    @include('common.timelineMessage', [
                        'timeline' => $timeline,
                    ])
                </action>
                <div class="d-flex align-items-center mt-2">
                    <div class="d-block small text-muted">
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-rounded me-1">
                                bookmark_border
                            </span>
                            <span>{{ trans('messages.timeline.type.' . $timeline->type) }}</span>
                        </div>
                    </div>
                    <desc class="d-block small text-muted small text-end activity-desc ms-auto">
                        <span class="material-symbols-rounded me-1">schedule</span> {{ Auth::user()->customer->formatDateTime($timeline->created_at, 'datetime_full') }}
                    </desc>
                </div>
            </div>
        </div>
    @endforeach
    <div class="mt-4">
        @include('elements/_per_page_select_short', ["items" => $timelines])
    </div>
    
@else
    <div class="empty-list">
        <i class="material-symbols-rounded">timeline</i>
        <span class="line-1">
            {{ trans('messages.automation.timeline.no_activities') }}
        </span>
    </div>
@endif