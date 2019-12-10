class Box {
    constructor(selector, url, callback) {
        this.box = selector;
        this.loadingHtml = '<div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
        
        if (typeof(url) !== 'undefined') {
            this.url = url;
        }
        
        if (typeof(callback) !== 'undefined') {
            this.callback = callback;
        }
    }
    
    loading() {
        this.box.prepend(this.loadingHtml);
    }
    
    applyJs() {
        var _this = this;
        
        // init js
        initJs(_this.box);
    }
    
    load(url, callback) {
        var _this = this;
        
        if (typeof(url) !== 'undefined') {
            this.url = url;
        }
        
        if (typeof(callback) !== 'undefined') {
            this.callback = callback;
        }
        
        this.loading();
        
        $.ajax({
            url: _this.url,
            type: 'GET',
            dataType: 'html',
        }).always(function(response) {
            _this.box.html(response);
            
            if (typeof(_this.callback) !== 'undefined') {
                _this.callback();
            }
            
            // apply js for new content
            _this.applyJs();
        });
    }
    
    loadHtml(html) {
        var _this = this;
        
        _this.box.html(html);
        
        _this.applyJs();
    }
}