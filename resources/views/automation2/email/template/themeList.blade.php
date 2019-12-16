@if ($templates->count() >0)
    <div class="template-list-items mt-3 clearfix">
        @foreach ($templates as $key => $template)
            <a href="" class="d-block template-list-item">
                <img href="javascript:;" src="{{ action('TemplateController@image', $template->uid) }}?v={{ rand(0,10) }}" />
                <label class="mb-20 text-center d-block mt-3">{{ $template->name }}</label>
            </a>
        @endforeach
    </div>
        
    @include('helpers._pagination')
@else
    <div class="empty-list">
        <i class="material-icons-outlined">featured_play_list</i>
        <span class="line-1">
            {{ trans('messages.automation.email.empty_template_list') }}
        </span>
    </div>
@endif
