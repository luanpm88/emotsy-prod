@php
    $value = isset($values[$field->tag]) ? $values[$field->tag] : $field->default_value;
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<div class="form-group {{ $errors->has('credits') ? 'has-error' : '' }}">
    <label>
        {{ $field->label }}
    </label>
    <div>
        <input
            {{ isset($list->getFieldRules()[$field->tag]) && $list->getFieldRules()[$field->tag] == 'required' ? 'required' : '' }}
            id="phone_{{ $field->uid }}_helper" type="text" name="{{ $field->tag }}_Helper" class="form-control"
            value="{{ $value }}">

        <input id="phone_{{ $field->uid }}" type="hidden" name="{{ $field->tag }}" value="{{ $value }}" />
    </div>
    @if ($errors->has($field->tag))
        <div class="help-block">
            {{ $errors->first($field->tag) }}
        </div>
    @endif
</div>

<script>
    const phoneInputFieldHelper = document.querySelector("#phone_{{ $field->uid }}_helper");
    const phoneInputField = document.querySelector("#phone_{{ $field->uid }}");
    const phoneInput = window.intlTelInput(phoneInputFieldHelper, {
        initialValue: '{{ $value }}',
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });

    $(function() {
        $(phoneInputField).closest('form').on('submit', function(e) {
            $(phoneInputField).val(phoneInput.getNumber());
        });
    });
</script>
