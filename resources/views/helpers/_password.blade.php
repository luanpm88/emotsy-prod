<div>
    <input type="password" id="{{ $name }}" value="{{ isset($value) ? $value : "" }}" autocomplete="new-password" type="text" name="{{ $name }}" class="form-control{{ $classes }} {{ isset($eye) && $eye == true ? "has-eye" : "" }}">
    @if (isset($eye) && $eye == true)
        <span class="icon icon-eye btn-view-password"></span>
    @endif
</div>
