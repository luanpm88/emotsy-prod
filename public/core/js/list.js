class List {
    constructor(options) {
        var _this = this;
        _this.id = '_' + Math.random().toString(36).substr(2, 9);
        _this.options = {};

        // options
        if (typeof(options) !== 'undefined') {
            _this.options = options;
        }
        
        // url
        if (typeof(options.url) !== 'undefined') {
            _this.url = options.url;
        } else {
            alert('List url is required!');
            return;
        }

        // content
        if (typeof(options.content) !== 'undefined') {
            _this.content = options.content;
        } else {
            alert('List content is required!');
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

    masking() {
        var _this = this;

        _this.content.css('opacity', '0.7');
    }

    unmasking() {
        var _this = this;
        
        _this.content.css('opacity', '1');
    }

    load() {
        var _this = this;

        if(_this.xhr && _this.xhr.readyState != 4){
            _this.xhr.abort();
        }

        _this.masking();

        _this.xhr = $.ajax({
            url: _this.url,
            method: _this.method,
            data: _this.data()
        }).done(function(response) {
            _this.content.html(response); 
            initJs(_this.content);

            // method
            if (typeof(_this.options.loaded) !== 'undefined') {
                _this.options.loaded();
            }

            _this.unmasking();
        }).fail(function(response) {

            _this.unmasking();
        });
    }
}