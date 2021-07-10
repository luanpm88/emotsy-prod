<div class="d-flex align-items-center">
    <div class="num_per_page mr-auto my-0">
        <select class="select" name="per_page">
            <option{{ $items->toArray()["per_page"] == "2" ? " selected" : "" }} value="2">2</option>
            <option{{ $items->toArray()["per_page"] == "15" ? " selected" : "" }} value="15">15</option>
            <option{{ $items->toArray()["per_page"] == "25" ? " selected" : "" }} value="25">25</option>
            <option{{ $items->toArray()["per_page"] == "50" ? " selected" : "" }} value="50">50</option>
            <option{{ $items->toArray()["per_page"] == "100" ? " selected" : "" }} value="100">100</option>
        </select>
        <label>{{ trans('messages.num_per_page') }}</label>
        @if (isset($items))
            <label>|
            {!! trans('messages.total_items_count', [
                "from" => $items->toArray()["per_page"]*($items->toArray()["current_page"]-1)+1,
                "to" => ($items->toArray()["per_page"]*$items->toArray()["current_page"] > $items->toArray()["total"] ? $items->toArray()["total"] : $items->toArray()["per_page"]*$items->toArray()["current_page"]),
                "count" => $items->toArray()["total"]]
            ) !!}
            <input type="hidden" name="total_items_count" value="{{ $items->toArray()["total"] }}" />
        @endif
    </div>
    <div class="d-flex align-items-center">@include('helpers._pagination', ['paginator' => $items])</div>
</div>
