@if (count($options))
    @include('helpers.form_control', [
        'type' => 'select',
        'name' => 'lists_segments[' . $index . '][segment_uids][]',
        'label' => trans('messages.which_segment_send'),
        'value' => '',
        'options' => $options,
        'multiple' => true,
    ])
@endif