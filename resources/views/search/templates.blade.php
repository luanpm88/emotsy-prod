@if ($templates->total())
    <a href="javascript:;" class="search-head border-bottom d-block">
        <label class="fw-600">
            <span class="material-icons-outlined me-1">
                web
                </span> {{ trans('messages.templates') }}
        </label>
    </a>
    @foreach($templates as $template)
        <a href="{{ action('TemplateController@index') }}" class="search-result border-bottom d-block">
            <div class="d-flex">
                <div>
                    <img width="40px" height="50px" class="shadow-sm me-3 rounded" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                </div>
                <div>
                    <label class="fw-600">
                        {{ $template->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0 text-nowrap">
                        {{ trans('messages.updated_at') }}: {{ Tool::formatDateTime($template->created_at) }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif