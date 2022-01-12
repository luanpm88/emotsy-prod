
<div class="headbar d-flex">
    <div class="me-auto"></div>
    @if(config('app.brand'))
        <div class="me-2">
            <a class="open-site-top-menu xtooltip" title="{{ trans('messages.open_website') }}" target="_blank" href="{{ config('wordpress.url') }}">
                <span class="material-icons-outlined">public</span>
            </a>
        </div>
    @endif
    <div class="top-search-container"></div>
</div>

<script>
    $(function() {
        TopSearchBar.init({
            container: $('.top-search-container'),
            sections: [
                new SearchSection({
                    url: '{{ action('SearchController@general') }}',
                }),
                new SearchSection({
                    url: '{{ action('SearchController@campaigns') }}',
                }),
                new SearchSection({
                    url: '{{ action('SearchController@lists') }}',
                }),
                new SearchSection({
                    url: '{{ action('SearchController@subscribers') }}',
                }),
                new SearchSection({
                    url: '{{ action('SearchController@automations') }}',
                }),
                new SearchSection({
                    url: '{{ action('SearchController@templates') }}',
                })
            ],
            lang: {
                no_keyword: `{!! trans('messages.search.type_to_search.wording') !!}`,
                empty_result: `{!! trans('messages.search.empty_result') !!}`,
                tooltip: `{!! trans('messages.click_open_app_search') !!}`,
            }
        });
    });
</script>