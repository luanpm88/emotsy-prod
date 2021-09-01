<div class="d-flex">
    <div class="app_search_box py-2 ms-auto d-flex align-items-center mt-2">
        <a href="javascript:;" class="search-close-button me-2">
            <span class="material-icons-round fs-4">close</span>
        </a>
        <div class="search-control d-flex justify-content-center">
            <input class="app_search_input form-control" type="text" placeholder="Type to search" />
            <span class="material-icons-round">
                search
            </span>
        </div>
        <div class="search-results shadow border">
        </div>
    </div>
</div>

<script>
    $(function() {
        TopSearchBar.init({
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
                no_keyword: '{!! trans('messages.search.type_to_search.wording') !!}',
                empty_result: '{!! trans('messages.search.empty_result') !!}',
            }
        });
    });
</script>