@if ($keyword)
    <div class="search-results-list">
        <div class="search-section general">
        </div>

        <div class="search-section campaigns">
        </div>


        {{-- <a href="" class="search-head border-bottom d-block">
            <label class="fw-600">
                <span class="material-icons-outlined me-1">
                    people
                    </span> Contacts
            </label>
        </a>
        <a href="" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <img class="shadow-sm" style="width:40px;border-radius:100%" src="https://i.pravatar.cc/300?1" />
                </div>
                <div>
                    <label class="fw-600">
                        Lois Pham
                    </label>
                    <p class="desc text-muted mt-1 mb-0">loispham@gmail.com</p>
                </div>
            </div>
                
        </a>
        <a href="" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <img class="shadow-sm" style="width:40px;border-radius:100%" src="https://i.pravatar.cc/300?2" />
                </div>
                <div>
                    <label class="fw-600">
                        Lois Pham
                    </label>
                    <p class="desc text-muted mt-1 mb-0">loispham@gmail.com</p>
                </div>
            </div>
                
        </a> --}}
    </div>

    <script>
        $(function() {
            var general = new SearchSection({
                url: '{{ action('SearchController@general', [
                    'keyword' => request()->keyword,
                ]) }}',
                hook: $('.search-section.general'),
                callback: function() {

                    // 2. Search campaigns
                    campaigns.load();
                }
            });

            var campaigns = new SearchSection({
                url: '{{ action('SearchController@campaigns', [
                    'keyword' => request()->keyword,
                ]) }}',
                hook: $('.search-section.campaigns'),
                callback: function() {
                }
            });

            // 1. Search general
            general.load();
        });
    </script>
@else
    <div class="">
        <div class="py-4 text-center">
            <div>
                <span class="material-icons-outlined fs-3 mb-2 text-muted2 me-2">
                    contact_support
                </span>
            </div>
            <span class="line-1 text-muted2">
                {{ trans('messages.search.no_result') }}
            </span>
        </div>
    </div>
@endif