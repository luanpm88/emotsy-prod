<select
    placeholder="{{ isset($placeholder) ? $placeholder : "" }}"
    class="select2-ajax {{ isset($class) ? $class : "" }}"
    name="{{ $name }}"
    data-url="{{ isset($url) ? $url : "" }}">
    @if (isset($selected) && isset($selected['value']))
        <option value="{{ $selected['value'] }}">{{ $selected['text'] }}</option>
    @endif
</select>