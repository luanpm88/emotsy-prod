@if ($templates->count() > 0)
    <div id="layout" class="tab-pane active template-boxes layout mt-4">
        @foreach ($templates as $key => $template)
            <a 
                href="{{ action('CampaignController@templateLayout', ['uid' => $campaign->uid, 'template' => $template->uid]) }}"
                class="select-template-layout choose-theme"
            >
                <div class="">
                    <div class="panel-template-style">
                        <div>
                            <div class="panel-template-placeholder">
                                <img src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                            </div>
                            <label class="mt-1 text-center">{{ $template->name }}</label>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
        
    <hr>
        
    @include('elements/_per_page_select', ["items" => $templates])
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <span class="material-icons-outlined">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-icons-outlined">
auto_awesome_mosaic
</span>
        <span class="line-1">
            {{ trans('messages.no_template_available') }}
        </span>
    </div>
@endif
