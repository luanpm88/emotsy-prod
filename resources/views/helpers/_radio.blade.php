    @foreach($options as $option)
        <div class="radio">
            <label>
                <input{!! isset($radio_group)  ? " radio-group='" . $radio_group . "'" : "" !!} {{ $option['value'] == $value  ? " checked" : "" }} type="radio" id="{{ $name }}" name="{{ $name }}" value="{{ $option['value'] }}" class="styled">
                {!! $option['text'] !!}
            </label>
        </div>
	@endforeach