@if ($contacts->count() > 0)
    <p class="insight-intro mb-2 small">
        {{ trans('messages.automation.contact.all_count', ['count' => format_number($pagination["total"])]) }}
    </p>
        
    <div class="mc-table small border-top">
        @foreach ($contacts as $key => $contact)
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
                        {{ $contact->email }} <span class="material-icons-outlined">launch</span>
                    </a>
                    <desc>{{ trans('messages.automation.contact.added_at', ['time' => $contact->created_at->diffForHumans()]) }}</desc>
                </div>
                <div class="flex-fill text-right">
                    <label title="" class="text-right">
                        <span class="">
                            <span class="text-warning">pending</span>
                        </span>
                    </label>
                    <desc class="text-nowrap">Est. trigger in 4 days on Dec 5</desc>
                </div>
            </div>
        @endforeach
        
    @include('helpers._pagination')
@else
    <div class="empty-list">
        <i class="lnr lnr-users"></i>
        <span class="line-1">
            {{ trans('messages.automation.empty_contacts') }}
        </span>
    </div>
@endif