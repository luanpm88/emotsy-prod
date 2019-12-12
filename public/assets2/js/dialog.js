class Dialog {
    constructor(type, title, message, callback) {
        var _this = this;
        this.dialog = $('.dialog');
        this.loadingHtml = '<div class="dialog-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
        
        if (type == null) {
            this.type = type;
        }
        
        if (title != null) {
            this.title = title;
        }
        
        if (message != null) {
            this.message = message;
        }
        
        if (callback != null) {
            this.callback = callback;
        }
        
        if (!this.dialog.length) {
            var dialog = $('<div class="dialog">').html(this.renderHtml(type));
            $('body').append(dialog);
            
            this.dialog = dialog;
        }
        this.dialog.css('display', 'none');
        
        this.load();
    }
    
    renderHtml(type) {
        switch(type) {
            case "confirm":
                if (this.title == null) {
                    this.title = LANG_ARE_YOU_SURE;
                }
                return `
                    <div class="dialog-container">
                        <div class="dialog-header">
                            <h2 class="title">`+this.title+`</h2>
                            <i class="lnr lnr-cross close"></i>
                        </div>
                        <div class="dialog-body">
                            `+this.message+`
                        </div>
                        <div class="dialog-footer">
                            <button class="btn btn-primary dialog-ok mr-2">`+LANG_OK+`</button>
                            <button class="btn btn-outline-secondary dialog-close mr-2">`+LANG_CANCEL+`</button>
                        </div>
                    </div>
                `;
            case y:
              // code block
              break;
            default:
              // code block
        }
    }
    
    show() {
        this.dialog.fadeIn();
        $('html').css('overflow', 'hidden');
    }
    
    hide() {
        this.dialog.fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    loading() {
        this.dialog.prepend(this.loadingHtml);
    }
    
    static hide() {
        $('.dialog').fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    applyJs() {
        var _this = this;
        
        // init js
        initJs(_this.dialog);
        
        // click close button
        _this.dialog.find(".close, .dialog-close").click(function() {
            _this.dialog.hide();
        });
        
        // click ok button
        _this.dialog.find(".dialog-ok").click(function() {
            _this.callback(_this);
            
            _this.hide();
        });
    }
    
    load(message, callback) {
        var _this = this;
        
        if (typeof(message) !== 'undefined') {
            _this.message = message;
        }
        
        if (typeof(callback) !== 'undefined') {
            _this.callback = callback;
        }
        
        _this.show();
        
        _this.dialog.find('.dialog-body').html(message);
        
        // apply js for new content
        _this.applyJs();
    }
    
    loadHtml(html) {
        var _this = this;
        
        _this.dialog.html(html);
        
        _this.applyJs();
    }
}

