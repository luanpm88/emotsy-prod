<div class="row">
    <div class="col-md-10">
        <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="file" name="{{ $name }}" class="file-styled-primary{{ $classes }} {{ isset($required) ? " required" : "" }}">
    </div>
    <div class="col-md-2">
        <img width="100%" src="" />
    </div>
</div>
