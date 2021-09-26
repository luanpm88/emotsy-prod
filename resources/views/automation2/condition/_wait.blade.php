@php
    $id = uniqid();
@endphp
<div id="condition_wait_container_{{ $id }}">
    <div class="mt-4 mb-4">
        <div class="">
            {!! trans('messages.condition.wait.title') !!}
        </div>
        <div class="mb-2 wait-select-container" style="width:100%">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'required',
                'label' => '',
                'name' => 'wait',
                'value' => $element->getOption('wait') ? $element->getOption('wait') : trans_choice('messages.count_day', 1),
                'required' => true,
                'options' => \Acelle\Model\Automation2::getConditionWaitOptions($element->getOption('wait')),
            ])
        </div>
        <div class="text-muted fst-italic">
            {!! trans('messages.condition.wait.intro', [
                'days' => $element->getOption('wait') ? trans_choice('messages.condition.wait_day', $element->getOption('wait')) : trans_choice('messages.count_day', 1)
            ]) !!}
        </div>
    </div>
</div>

<script>
    var Automation2ConditionWait = {
        labels: $('#condition_wait_container_{{ $id }} .wait_day'),
        selectBox: function() {
            return $('#condition_wait_container_{{ $id }} [name=wait]');
        },
        selectContainer: $('#condition_wait_container_{{ $id }} .wait-select-container'),

        customUrl: '{{ action('Automation2Controller@conditionWaitCustom') }}',

        select: function() {
            var _this = this;

            var val = _this.selectBox().val();
            var text = _this.selectBox().find('option:selected').html();

            if (val == 'custom') {
                _this.showCustomPopup();
                return;
            }

            _this.labels.html(text);
        },

        init: function() {
            var _this = this;

            _this.selectBox().on('change', function() {
                console.log('dddd');
                _this.select();
            });
        },

        showCustomPopup: function() {
            var _this = this;
            
            _this.customPopup = new Popup({
                url: _this.customUrl
            });

            _this.customPopup.load();
        }
    }

    $(function() {
        Automation2ConditionWait.init();
    });
</script>