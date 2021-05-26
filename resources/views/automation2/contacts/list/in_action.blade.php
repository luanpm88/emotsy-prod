@if ($contacts->count() > 0)
    <p class="insight-intro mb-2 small">
        {{ trans('messages.automation.contact.all_count', ['count' => format_number($contacts->total())]) }}
    </p>
        
    <div class="mc-table small border-top">
        @foreach ($contacts as $key => $contact)
            @php
                $trigger = $automation->getAutoTriggerFor($contact);
            @endphp
            <div class="mc-row d-flex align-items-center">
                <div class="media trigger">
                    <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
                        'uid' => $automation->uid,
                        'contact_uid' => $contact->uid,
                    ]) }}')" class="font-weight-semibold d-block">
                        @if ($contact->avatar)
                            <img src="{{ action('SubscriberController@avatar',  $contact->uid) }}" haha="https://i.pravatar.cc/30{{ $key }}" />
                        @elseif(isSiteDemo())
                            <img src="https://i.pravatar.cc/30{{ $key }}" />
                        @else
                            <i style="opacity: 0.7" class="lnr lnr-user bg-{{ rand_item(['info', 'success', 'secondary', 'primary', 'danger', 'warning']) }}"></i>
                        @endif
                    </a>
                </div>
                <div class="flex-fill" style="width: 40%">
                    <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
                        'uid' => $automation->uid,
                        'contact_uid' => $contact->uid,
                    ]) }}')" class="font-weight-semibold d-block">
                        {{ $contact->email }}
                    </a>
                    <desc>{{ trans('messages.automation.contact.added_at', ['time' => $contact->created_at->diffForHumans()]) }}</desc>
                </div>
                
                <div class="actions-points">
                    @if (!is_null($trigger))
                        @foreach ($trigger->getExecutedActions() as $action)
                            <span class="xtooltip round-action-point action-{{ $action->getType() }}" title="{{ $action->getProgressDescription() }}">
                            </span>
                        @endforeach
                    @endif
                </div>
                <div class="flex-fill text-right">
                    @if (is_null($trigger))
                        <label title="" class="text-right">
                            <span class="text-truncate" style="max-width: 100px;margin-right:0px">
                                not triggered
                            </span>
                        </label>
                        <desc><a target="_blank" style="color:blue;text-decoration: underline;" href="{{ action('Automation2Controller@triggerNow', [ 'automation' => $automation->uid, 'subscriber' => $contact->uid ]) }}">Trigger Now</a></desc>
                    @else
                        <label title="" class="text-right">
                            <span class="text-truncate" style="max-width: 100px;margin-right:0px">
                                @php
                                    $latestAction = $trigger->getLatestAction() ?: $trigger->getTrigger();

                                    if (!is_null($latestAction->getLastExecuted())) {
                                        $update = \Carbon\Carbon::createFromTimestamp($latestAction->getLastExecuted());
                                    } else {
                                        $update = $trigger->created_at;
                                    }
                                @endphp

                                {{ $update->diffForHumans() }}
                            </span>
                        </label>
                        <desc>Last activity • <a target="_blank" target="_blank" style="color:blue;text-decoration: underline;" href="{{ action('AutoTrigger@check', [ 'id' => $trigger->id ]) }}">Check</a></desc>
                    @endif
                    
                </div>
            </div>
        @endforeach
        
    @include('helpers._pagination', ['paginator' => $contacts] )
@else
    <div class="empty-list">
        <i class="lnr lnr-users"></i>
        <span class="line-1">
            {{ trans('messages.automation.empty_contacts') }}
        </span>
    </div>
@endif