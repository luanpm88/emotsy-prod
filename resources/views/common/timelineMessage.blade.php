
@switch($timeline->type)
    @case(\Acelle\Model\Timeline::TYPE_ADDED)
        {{ trans('messages.timeline.message.added', [
            'customer' => $timeline->customer->displayName(),
        ]) }}
        @break
    @case(2)
        
        @break
    @default
        
@endswitch