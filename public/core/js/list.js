class List {
    constructor(options) {
        var _this = this;
        this.id = '_' + Math.random().toString(36).substr(2, 9);
        this.options = {};

        // options
        if (typeof(options) !== 'undefined') {
            this.options = options;
        }
        
        // url
        if (typeof(options.url) !== 'undefined') {
            _this.url = options.url;
        } else {
            alert('List url is required!');
            return;
        }

        // container
        if (typeof(options.container) !== 'undefined') {
            _this.container = options.container;
        } else {
            alert('List container is required!');
            return;
        }
        
        // data
        if (typeof(options.data) !== 'undefined') {
            _this.data = options.data;
        } else {
            _this.data = {};
        }

        // method
        if (typeof(options.method) !== 'undefined') {
            _this.method = options.method
        } else {
            _this.method = 'GET';
        }
    }

    load() {
        var _this = this;

        $.ajax({
            url: _this.url,
            method: _this.method,
            data: _this.data()
        }).done(function(response) {
            _this.container.html(response); 
            initJs(_this.container);
        }).fail(function(response) {
            console.log(response);
        });
    }
}