
<div class="headbar">
    @include('layouts.core._top_search')
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
        });
    });
</script>