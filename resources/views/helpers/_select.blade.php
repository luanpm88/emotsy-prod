<select name="{{ $name }}" class="select-search{{ $classes }}">
	@if (isset($include_blank))
		<option value="">{{ $include_blank }}</option>
	@endif
	@foreach($options as $option)
		<option{{ $option['value'] == $value ? " selected" : "" }} value="{{ $option['value'] }}">{{ $option['text'] }}</option>
	@endforeach
</select>