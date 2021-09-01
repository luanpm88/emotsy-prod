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
                    <div class="mb-2">
                        <svg style="width:60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 424.2 380.5"><defs><clipPath id="clip-path"><circle cx="150" cy="149" r="144" style="fill:none"/></clipPath></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M422,127.9v42.4c0,7.6-8.4,13.8-18.7,13.8H294V114H403.3C413.6,114,422,120.2,422,127.9Z" style="fill:#fff;opacity:0.13"/><path d="M294,184.1H402.7c10.3,0,18.6-6.2,18.6-13.8V127.8c0-7.6-8.3-13.8-18.6-13.8H294" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.769283168228909px"/><g style="clip-path:url(#clip-path)"><rect x="73.7" y="102.1" width="296.3" height="93.89" rx="18.6" style="fill:#fff;opacity:0.13"/><path d="M186.2,102.1H350.8a18.5,18.5,0,0,1,18.5,18.6v56.8A18.5,18.5,0,0,1,350.8,196H186.2" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:6.659193055820083px"/><path d="M160.2,196H91.5A18.5,18.5,0,0,1,73,177.5V120.7a18.5,18.5,0,0,1,18.5-18.6h68.7" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:6.659193055820083px"/><path d="M154.6,219.3h.1a17.9,17.9,0,0,0,17.9-17.8V96.4a17.9,17.9,0,0,0-17.9-17.9h-.1" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.983098520979932px"/><path d="M190.5,78.5h-.1a17.9,17.9,0,0,0-17.8,17.9V201.5a17.8,17.8,0,0,0,17.8,17.8h.1" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.983098520979932px"/><path d="M138.3,167l-.6-4.9h-.2a14.6,14.6,0,0,1-11.9,5.8c-7.8,0-11.8-5.6-11.8-11.2,0-9.3,8.3-14.4,23.3-14.3v-.8c0-3.2-.9-9-8.8-9a19,19,0,0,0-10.1,2.9l-1.6-4.6a24,24,0,0,1,12.7-3.5c11.9,0,14.7,8.1,14.7,15.8v14.5a52.6,52.6,0,0,0,.7,9.3Zm-1.1-19.8c-7.6-.1-16.3,1.2-16.3,8.7,0,4.6,3,6.8,6.6,6.8a9.6,9.6,0,0,0,9.3-6.5,5.9,5.9,0,0,0,.4-2.3Z" style="fill:gray"/></g><g style="opacity:0.13"><circle cx="150" cy="149" r="149" style="fill:#0071bc"/></g><path d="M149.5,10A139.6,139.6,0,0,1,289,149.5,139.6,139.6,0,0,1,149.5,289,139.6,139.6,0,0,1,10,149.5,139.6,139.6,0,0,1,149.5,10m0-10A149.5,149.5,0,1,0,299,149.5,149.5,149.5,0,0,0,149.5,0Z" style="fill:#0071bc"/><line x1="245" y1="264" x2="356.5" y2="375.5" style="fill:none;stroke:#0071bc;stroke-linecap:round;stroke-linejoin:round;stroke-width:10px"/><path d="M28,150v-1A122.2,122.2,0,0,1,102,36.8" style="fill:none;stroke:#0071bc;stroke-linecap:round;stroke-linejoin:round;stroke-width:10px"/></g></g></svg>
                    </div>
                    <p class="line-1 px-4 mb-0" style="font-size: 92%">
                        ` + TopSearchBar.lang.no_keyword + `
                    </p>
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
    
    abortAll: function() {
        this.sections.forEach(function(section) {
            if(section.xhr != null && section.xhr.readyState != 4){
                section.xhr.abort();
            }
        });
    },

    search: function() {
        if (!TopSearchBar.getKeyword()) {
            TopSearchBar.abortAll();
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
        TopSearchBar.container = options.container;

        // html structure
        TopSearchBar.container.html(`
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
        `);

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