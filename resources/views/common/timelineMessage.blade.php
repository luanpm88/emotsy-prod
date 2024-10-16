
@switch($timeline->type)
    @case(\Acelle\Model\Timeline::TYPE_ADDED_BY_CUSTOMER)
        {!! trans('messages.timeline.message.added_by_customer', [
            'list' => $timeline->mailList->name,
            'customer' => $timeline->customer->displayName(),
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_SIGN_UP_FORM_OPT_IN)
        {!! trans('messages.timeline.message.sign_up_form_opt_in', [
            'list' => $timeline->mailList->name,
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_EMBEDDED_FORM_OPT_IN)
        {!! trans('messages.timeline.message.embedded_form_opt_in', [
            'list' => $timeline->mailList->name,
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_POPUP_FORM_OPT_IN)
        {!! trans('messages.timeline.message.popup_form_opt_in', [
            'list' => $timeline->form->mailList->name,
            'form' => $timeline->form->name,
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_UNSUBSCRIBED_BY_CUSTOMER)
        {!! trans('messages.timeline.message.unsubscribed_by_customer', [
            'list' => $timeline->mailList->name,
            'customer' => $timeline->customer->displayName(),
        ]) !!}
    @break
        @case(\Acelle\Model\Timeline::TYPE_SUBSCRIBED_BY_CUSTOMER)
        {!! trans('messages.timeline.message.subscribed_by_customer', [
            'list' => $timeline->mailList->name,
            'customer' => $timeline->customer->displayName(),
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_API_ADDED)
        {!! trans('messages.timeline.message.api_added', [
            'list' => $timeline->mailList->name,
            'customer' => $timeline->customer->displayName(),
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_API_SUBSCRIBED)
        {!! trans('messages.timeline.message.api_subscribed', [
            'list' => $timeline->mailList->name,
            'customer' => $timeline->customer->displayName(),
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_COPIED_TO)
        {!! trans('messages.timeline.message.copied_to', [
            'list' => $timeline->mailList->name,
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_COPIED_FROM)
        {!! trans('messages.timeline.message.copied_from', [
            'list' => $timeline->mailList->name,
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_MOVED_FROM)
        {!! trans('messages.timeline.message.moved_from', [
            'list' => $timeline->mailList->name,
        ]) !!}
        @break
    @case(\Acelle\Model\Timeline::TYPE_UNSUBSCRIBED_FROM_LIST_UNSUBSCRIBE_FORM)
        {!! trans('messages.timeline.message.unsubscribed_from_list_unsubscribe_form', [
            'list' => $timeline->mailList->name,
        ]) !!}
        @break
    @default
        
@endswitch