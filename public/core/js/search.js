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
            TopSearchBar.getResultsBox().append(`<div class="search-section" id="`+this.id+`"></div>`);
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
        if (result == null) {
            return;
        }

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

    findPrev: function() {
        // current section
        if (TopSearchBar.getSelected().prevAll('.search-result').length) {
            return TopSearchBar.getSelected().prevAll('.search-result').first();
        }

        // next section
        if (TopSearchBar.getSelected().closest('.search-section').prevAll('.search-section').length) {
            if (TopSearchBar.getSelected().closest('.search-section').prevAll('.search-section').find('.search-result').length) {
                return TopSearchBar.getSelected().closest('.search-section').prevAll('.search-section').find('.search-result').last();
            }
        }

        return null;
    },

    moveUp: function() {
        if (!TopSearchBar.getResults().length) {
            return;
        }

        if (!TopSearchBar.getSelected().length) {
            TopSearchBar.select(TopSearchBar.getResults().first());
            return;
        }

        if (!TopSearchBar.findPrev() == null) {
            return;
        }

        TopSearchBar.select(TopSearchBar.findPrev());
    },

    scrollUp: function() {
        TopSearchBar.getResultsBox().animate({
            scrollTop: TopSearchBar.getResultsBox().scrollTop() + TopSearchBar.getSelected().height()*4
        }, 100);
    },

    scrollDown: function() {
        TopSearchBar.getResultsBox().animate({
            scrollTop: TopSearchBar.getResultsBox().scrollTop() - TopSearchBar.getSelected().height()*4
        }, 100);
    },

    findNext: function() {
        // current section
        if (TopSearchBar.getSelected().nextAll('.search-result').length) {
            return TopSearchBar.getSelected().nextAll('.search-result').first();
        }

        // next section
        if (TopSearchBar.getSelected().closest('.search-section').nextAll('.search-section').length) {
            if (TopSearchBar.getSelected().closest('.search-section').nextAll('.search-section').find('.search-result').length) {
                return TopSearchBar.getSelected().closest('.search-section').nextAll('.search-section').find('.search-result').first();
            }
        }

        return null;
    },

    moveDown: function() {
        if (!TopSearchBar.getResults().length) {
            return;
        }

        if (!TopSearchBar.getSelected().length) {
            TopSearchBar.select(TopSearchBar.getResults().first());
            return;
        }

        if (!TopSearchBar.findNext() == null) {
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
                        ` + TopSearchBar.lang.no_keyword + `
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
                            </span> ` + TopSearchBar.lang.empty_result + `
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
        
        TopSearchBar.current = 0;
        if (typeof(TopSearchBar.sections[TopSearchBar.current]) != 'undefined') {
            TopSearchBar.runSection(TopSearchBar.sections[TopSearchBar.current]);
        }
    },

    runSection: function(section) {
        if (typeof(section) == 'undefined') {
            TopSearchBar.checkEmptyResult();
            return;
        }

        section.load({
            callback: function() {
                TopSearchBar.current += 1;
                TopSearchBar.runSection(TopSearchBar.sections[TopSearchBar.current]);
            }
        });
    },

    init: function(options) {
        // search sections
        TopSearchBar.sections = options.sections;
        TopSearchBar.lang = options.lang;

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
    }
};