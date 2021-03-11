<h5 class="mb-3">
    {{ trans('messages.automation.action.wait') }}
</h5>
<p class="mb-3">
    {{ trans('messages.automation.action.wait.intro') }}
</p>

<div class="row">
    @php
        $delayOptions = $automation->getDelayOptions();
        $parts = explode(' ', $element->getOption('time'));
        $exist = false;
        $text = trans('messages.time.wait_for') . ' ' . $parts[0] . ' ' . trans_choice('messages.time.' . $parts[1], (int) $parts[0]);

        foreach($delayOptions as $deplayOption) {
            if ($deplayOption['value'] == $element->getOption('time')) {
                $exist = true;
            }
        }

        $moreOptions = [];
        if (!$exist) {
            $moreOptions = [['text' => $text, 'value' => $text]];
        }
    @endphp
    <div class="col-md-6 wait-time">    
        @include('helpers.form_control', [
            'type' => 'select',
            'class' => '',
            'label' => '',
            'name' => 'time',
            'value' => $element->getOption('time'),
            'help_class' => 'trigger',
            'options' => $automation->getDelayOptions($moreOptions),
            'rules' => [],
        ])
        <div class="custom-wait-time"></div>
    </div>
</div>

<script>
    var waitTimePopup = new Popup();

    $('.wait-time [name=time]').change(function() {
        var val = $(this).val();

        if (val == 'custom') {
            waitTimePopup.load('{{ action('Automation2Controller@waitTime', $automation->uid) }}');
        }
    });
</script>