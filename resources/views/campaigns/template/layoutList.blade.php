@if ($templates->count() > 0)
    <div id="layout" class="row tab-pane fade in show active template-boxes layout mt-4" style="
        margin-left: -20px;
        margin-right: -20px;
    ">
        @foreach ($templates as $key => $template)
            <div class="col-md-2 mb-4">
                <a 
                    href="{{ action('CampaignController@templateLayout', ['uid' => $campaign->uid, 'template' => $template->uid]) }}"
                    class="choose-theme"
                >
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <div>
                                <div class="panel-template-placeholder">
                                    <img src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                                </div>
                                <label class="mb-20 text-center">{{ $template->name }}</label>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
        
    <hr style="clear: both">
        
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
