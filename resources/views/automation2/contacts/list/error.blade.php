@if ($contacts->count() > 0)
    <p class="insight-intro mb-2 small">
        {{ trans('messages.automation.contact.all_count', ['count' => format_number($pagination["total"])]) }}
    </p>
        
    <div class="mc-table small border-top">
        @foreach ($contacts as $contact)
            <div class="mc-row d-flex align-items-center contact-error-row">
                @include('automation2.contacts.list.error_row')   
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