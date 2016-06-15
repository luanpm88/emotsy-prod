<div class="input-icon-right">											
    <input {{ isset($disabled) && $disabled == true ? ' disabled="disabled"' : "" }} id="{{ $name }}" placeholder="{{ isset($placeholder) ? $placeholder : "" }}" value="{{ isset($value) ? $value : "" }}" type="text" name="{{ $name }}" class="form-control{{ $classes }} pickadate">
    <span class=""><i class="icon-calendar22"></i></span>
</div>
