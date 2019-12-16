<pagination class="d-flex d-block align-items-center mt-3">
    <label class="small mb-0 mr-auto">
        {!! trans('messages.pagination.page_of', [
            'pages' => $pagination["pageCount"],
            'page' => $pagination["page"] + 1,
        ]) !!}        
    </label>
    <ul class="pagination justify-content-end small align-items-center mb-0">
        <li class="page-item {{ $pagination["page"] == 0 ? 'disabled' : '' }}">
            <a class="page-link" href="javascript:;" tabindex="-1" aria-disabled="true" data-page="{{ $pagination["page"]-1 }}">{{ trans('messages.pagination.previous') }}</a>
        </li>
        @for ($i = 0; $i < $pagination["pageCount"]; $i++)
            @if ($i == $pagination["page"])
                <li class="page-item active" aria-current="page">
                    <span class="page-link">
                        {{ $pagination["page"] + 1 }}
                        <span class="sr-only">(current)</span>
                    </span>
                </li>                    
            @else
                <li class="page-item"><a class="page-link" href="javascript:;" data-page="{{ $i }}">{{ $i + 1 }}</a></li>
            @endif
        @endfor
        <li class="page-item {{ $pagination["page"] == $pagination["pageCount"]-1 ? 'disabled' : '' }}">
          <a class="page-link" href="javascript:;" data-page="{{ $pagination["page"]+1 }}">{{ trans('messages.pagination.next') }}</a>
        </li>
    </ul>
</pagination>