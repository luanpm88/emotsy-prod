<div class="condition_wait_container">
    <div class="mt-4">
        {!! trans('messages.condition.wait_intro', [
            'days' => $element->getOption('wait') ? trans_choice('messages.condition.wait_day', $element->getOption('wait')) : trans_choice('messages.condition.wait_day', 2)
        ]) !!}
    </div>

    <div class="mb-4" style="width:100%">
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => 'required',
            'label' => '',
            'name' => 'wait',
            'value' => $element->getOption('wait') ? $element->getOption('wait') : 2,
            'required' => true,
            'options' => $automation->getConditionWaitOptions(),
        ])
    </div>
</div>

<script>
    $(function() {
        $('[name=wait]').on('change', function() {
            var text = $(this).find('option:selected').html();

            $(this).closest('.condition_wait_container').find('.wait_day').html(text);
        });
    });
</script>