    @foreach($options as $option)
        <div class="radio">
            <label data-popup='tooltip' title="{{ isset($popup) ? $popup : '' }}">
                <input{!! isset($radio_group)  ? " radio-group='" . $radio_group . "'" : "" !!} {{ $option['value'] == $value  ? " checked" : "" }} type="radio" id="{{ $name }}" name="{{ $name }}" value="{{ $option['value'] }}" class="styled">
                {!! $option['text'] !!}
            </label>
        </div>
	@endforeach