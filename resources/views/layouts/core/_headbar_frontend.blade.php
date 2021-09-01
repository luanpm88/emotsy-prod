<div class="headbar">
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
</div>

<script>
    class SearchSection {
        constructor(options) {
            this.id = '_' + Math.random().toString(36).substr(2, 9);

            for (const [key, value] of Object.entries(options)) {
                this[key] = value;
            }
        }

        getContainer() {
            // check exist
            if (!$('#' + this.id).length) {
                TopSearchBar.getResultsBox().append(`<div id="`+this.id+`"></div>`);
            }

            return $('#' + this.id);
        }

        load(options) {
            var _this = this;

            if (typeof(options) !== 'undefined') {
                for (const [key, value] of Object.entries(options)) {
                    this[key] = value;
                }
            }

            // loading spinner
            if (typeof(_this.before) != 'undefined') {
                _this.before();
            }

            if(this.xhr != null && this.xhr.readyState != 4){
                this.xhr.abort();
            }
            this.xhr = $.ajax({
                url: _this.url,
                type: 'GET',
                data: {
                    keyword: TopSearchBar.getKeyword()
                }
            }).done(function(response) {
                _this.getContainer().html(response);

                if (typeof(_this.callback) != 'undefined') {
                    _this.callback();
                }
            });
        }
    }

    var TopSearchBar = {
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

        getResults: function() {
            return $('.search-result');
        },

        getSelected: function() {
            return $('.search-result.selected');
        },

        openSearch: function() {
            $('body').addClass('search-open');
            TopSearchBar.getSearchInput().focus();
            TopSearchBar.getSearchInput()[0].setSelectionRange(0, TopSearchBar.getSearchInput()[0].value.length)
        },

        closeSearch: function() {
            $('body').removeClass('search-open');
        },

        isSearchOpen: function() {
            return $('body').hasClass('search-open');
        },

        getKeyword: function() {
            return TopSearchBar.getSearchInput().val().trim();
        },



        unselect: function() {
            if (TopSearchBar.getSelected().length) {
                TopSearchBar.getSelected().removeClass('selected');
            }
        },

        go: function() {
            if (!TopSearchBar.getResults().length) {
                return;
            }

            if (!TopSearchBar.getSelected().length) {
                TopSearchBar.select(TopSearchBar.getResults().first());
            }

            window.location = TopSearchBar.getSelected().attr('href');
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
            if (!TopSearchBar.getResults().length) {
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

        findNext: function() {
            return TopSearchBar.getSelected().nextAll('.search-result').first();
        },

        moveDown: function() {
            if (!TopSearchBar.getResults().length) {
                return;
            }

            if (!TopSearchBar.getSelected().length) {
                TopSearchBar.select(TopSearchBar.getResults().first());
                return;
            }

            if (!TopSearchBar.findNext().length) {
                return;
            }

            TopSearchBar.select(TopSearchBar.findNext());
        },

        showNoKeyword: function() {
            TopSearchBar.getResultsBox().html(`
                <div class="no-keyword-alert">
                    <div class="py-4 text-center">
                        <div>
                            <span class="material-icons-outlined fs-1 mb-2 text-muted2 me-2">
                                category
                            </span>
                        </div>
                        <span class="line-1 text-muted2 px-4">
                            {!! trans('messages.search.type_to_search.wording') !!}
                        </span>
                    </div>
                </div>
            `);
        },

        hideNoKeyword: function() {
            TopSearchBar.getResultsBox().find('.no-keyword-alert').remove();
        },

        checkEmptyResult: function() {
            if (!this.getResults().length) {
                TopSearchBar.getResultsBox().html(`
                    <div class="empty-result-alert">
                        <div class="py-3 text-center">
                            <span class="line-1 text-muted2 px-4">
                                <span class="material-icons-outlined mb-2 me-2">
                                    feedback
                                </span> {!! trans('messages.search.empty_result') !!}
                            </span>
                        </div>
                    </div>
                `);
            } else {
                TopSearchBar.getResultsBox().find('.empty-result-alert').remove();
            }
        },

        search: function() {
            var _this = this;

            if (!TopSearchBar.getKeyword()) {
                TopSearchBar.showNoKeyword();
                return;
            } else {
                TopSearchBar.hideNoKeyword();
            }

            TopSearchBar.getGeneralSection().load({
                callback: function() {
                    TopSearchBar.getCampaignsSection().load({
                        callback: function() {
                            _this.checkEmptyResult();
                        }
                    });
                    
                }
            });
        },

        generalSection: null,

        getGeneralSection: function() {
            if (this.generalSection == null) {
                this.generalSection = new SearchSection({
                    url: '{{ action('SearchController@general') }}',
                });
            }

            return this.generalSection;
        },

        campaignsSection: null,

        getCampaignsSection: function() {
            if (this.campaignsSection == null) {
                this.campaignsSection = new SearchSection({
                    url: '{{ action('SearchController@campaigns') }}',
                    before: function() {
                    },
                    callback: function() {
                    }
                });
            }
            return this.campaignsSection;
        },
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

        $('.search-close-button').on('click', function() {
            TopSearchBar.closeSearch();
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