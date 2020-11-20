
    <div class="media trigger">
        <a href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@profile', [
            'uid' => $automation->uid,
            'contact_uid' => $contact->uid,
        ]) }}')" class="font-weight-semibold d-block">
            @if ($contact->avatar)
                <img src="{{ action('SubscriberController@avatar',  $contact->uid) }}" haha="https://i.pravatar.cc/300?v={{ $contact->uid }}" />
            @elseif(isSiteDemo())
                <img src="https://i.pravatar.cc/30?v={{ $contact->uid }}" />
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
    <div class="actions-points">
        @foreach ($automation->getInsight() as $key => $element)
            @php
                $action = $automation->getElement($key);
            @endphp

            <span class="round-action-point action-{{ $action->get('type') }}" title="{{ $action->getName() }}">
            </span>
        @endforeach
    </div>
    <div class="flex-fill text-right">
        <label title="" class="text-right">
            <span class="text-truncate small font-weight-semibold d-block m-auto text-danger" style="max-width: 100px;">
                Error
            </span>
        </label>
        <desc>{{ trans('messages.automation.last_activity') }}</desc>
    </div>
    <div class="flex-fill text-right">
        <button data-url="{{ action('Automation2Controller@contactRetry', [
            'uid' => $automation->uid,
            'contact_uid' => $contact->uid,
        ]) }}" class="btn btn-secondary btn-sm ml-2 error-retry-{{ $contact->uid }}">
            {{ trans('messages.automation.retry') }}
        </button>
    </div>

    <script>
        $('.error-retry-{{ $contact->uid }}').click(function(e) {
            e.preventDefault();

            var box = new Box($(this).closest('.contact-error-row'));

            box.load($(this).attr('data-url'), function() {
                // notify
                notify('success', '{{ trans('messages.notify.success') }}', 'retried!');
            });
        });
        
    </script>