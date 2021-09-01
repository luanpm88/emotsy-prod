@if (count($results))
    <a href="javascript:;" class="search-head border-bottom d-block">
        <label class="fw-600">
            <span class="material-icons-outlined me-1">
                manage_search 
                </span> {{ trans('messages.search.general') }}
        </label>
    </a>
    @foreach($results as $result)
        <a href="{{ $result['item']['url'] }}" class="search-result border-bottom d-block">
            <label class="fw-600">
                @foreach($result['item']['names'] as $name)
                    <span class="search-name">{{ $name }}</span>
                @endforeach
            </label>
            @if (isset($result['item']['desc']))
                <p class="desc text-muted mt-1 mb-0">{{ $result['item']['desc'] }}</p>
            @endif
        </a>
    @endforeach
@endif