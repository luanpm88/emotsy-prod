<div class="headbar">
    <div class="d-flex">
        <div class="app_search_box py-2 ms-auto">
            <div class="search-control d-flex justify-content-center">
                <input class="app_search_input form-control" type="text" placeholder="Type to search" />
                <span class="material-icons-round">
                    search
                </span>
            </div>
            <div class="search-results shadow border">
                <div class="list-loading"></div>
            </div>
        </div>
    </div>
</div>

<script>
    class SearchSection {
        constructor(options) {
            for (const [key, value] of Object.entries(options)) {
                this[key] = value;
            }
        }

        load(options) {
            var _this = this;

            // loading spinner
            _this.hook.html(`<div class="search-box-loading"></div>`);

            $.ajax({
                url: _this.url,
                type: 'GET'
            }).done(function(response) {
                _this.hook.replaceWith(response);

                if (typeof(_this.callback) != 'undefined') {
                    _this.callback();
                }
            });
        }
    }

    var TopSearchBar = {
        url: '{{ action('SearchController@index') }}',

        getSearchBox: function() {
            return $('.app_search_box');
        },

        getSearchInput: function() {
            return $('.app_search_input');
        },
        
        getSearchControl: function() {
            return $('.search-control');
        },

        getResultsBox: function() {
            return $('.search-results');
        },

        getResultsList: function() {
            return $('.search-results-list');
        },

        getResults: function() {
            return $('.search-result');
        },

        getSelected: function() {
            return $('.search-result.selected');
        },

        openSearch: function() {
            $('body').addClass('search-open');
            TopSearchBar.getSearchInput().focus();
        },

        closeSearch: function() {
            $('body').removeClass('search-open');
        },

        isSearchOpen: function() {
            return $('body').hasClass('search-open');
        },

        unselect: function() {
            if (TopSearchBar.getSelected().length) {
                TopSearchBar.getSelected().removeClass('selected');
            }
        },

        go: function() {
            if (TopSearchBar.getSelected().length) {
                window.location = TopSearchBar.getSelected().attr('href');
            }
        },

        select: function(result) {
            TopSearchBar.unselect();
            result.addClass('selected');

            // scroll up
            var resultBottomOffset = TopSearchBar.getSelected().offset().top + TopSearchBar.getSelected().height();
            var searchBoxBottomOffset = TopSearchBar.getResultsBox().offset().top + TopSearchBar.getResultsBox().height();
            if (resultBottomOffset - searchBoxBottomOffset > -20) {
                TopSearchBar.scrollUp();
                return;
            }

            // scroll down
            var resultTopOffset = TopSearchBar.getSelected().offset().top;
            var searchBoxTopOffset = TopSearchBar.getResultsBox().offset().top;
            console.log(resultTopOffset);
            console.log(searchBoxTopOffset);
            if (resultTopOffset - searchBoxTopOffset < 20) {
                TopSearchBar.scrollDown();
            }
        },

        moveUp: function() {
            if (!TopSearchBar.getResultsList().length) {
                return;
            }

            if (!TopSearchBar.getSelected().length) {
                TopSearchBar.select(TopSearchBar.getResults().first());
                return;
            }

            if (!TopSearchBar.getSelected().prevAll('.search-result').length) {
                return;
            }

            TopSearchBar.select(TopSearchBar.getSelected().prevAll('.search-result').first());
        },

        scrollUp: function() {
            TopSearchBar.getResultsBox().animate({
                scrollTop: TopSearchBar.getResultsBox().scrollTop() + TopSearchBar.getSelected().height()*3
            }, 200);
        },

        scrollDown: function() {
            TopSearchBar.getResultsBox().animate({
                scrollTop: TopSearchBar.getResultsBox().scrollTop() - TopSearchBar.getSelected().height()*3
            }, 200);
        },

        moveDown: function() {
            if (!TopSearchBar.getResultsList().length) {
                return;
            }

            if (!TopSearchBar.getSelected().length) {
                TopSearchBar.select(TopSearchBar.getResults().first());
                return;
            }

            if (!TopSearchBar.getSelected().nextAll('.search-result').length) {
                return;
            }

            TopSearchBar.select(TopSearchBar.getSelected().nextAll('.search-result').first());
        },

        search: function() {
            // copy
            if(TopSearchBar.xhr != null && TopSearchBar.xhr.readyState != 4){
                TopSearchBar.xhr.abort();
            }
            TopSearchBar.xhr = $.ajax({
                url: TopSearchBar.url,
                type: 'GET',
                data: {
                    keyword: TopSearchBar.getSearchInput().val()
                }
            }).done(function(response) {
                TopSearchBar.getResultsBox().html(response);
            });
        }
    };

    $(function() {
        TopSearchBar.getSearchControl().on('click', function() {
            TopSearchBar.openSearch();
            TopSearchBar.search();
        });

        TopSearchBar.getSearchInput().on('keyup', function(e) {
            if (e.which == 40 || e.which == 38 || e.which == 13) {
                return;
            }
            TopSearchBar.search();
        });

        // clid ouside hide search box
        $(document).on('mouseup', function(e) 
        {
            var container = TopSearchBar.getSearchBox();

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                TopSearchBar.closeSearch();
            }
        });

        // biding arrows key
        $(document).on('keydown', function(e) {
            if (!TopSearchBar.isSearchOpen()) {
                return;
            }
            switch(e.which) {
                case 13: // enter
                    TopSearchBar.go();
                break;

                case 37: // left
                break;

                case 38: // up
                    TopSearchBar.moveUp();
                break;

                case 39: // right
                break;

                case 40: // down
                    TopSearchBar.moveDown();
                break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });
    });
</script>