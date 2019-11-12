class Popup {
    constructor(url, callback) {
        var _this = this;
        this.popup = $('.popup');
        this.loadingHtml = '<div class="popup-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
        
        if (typeof(url) !== 'undefined') {
            this.url = url;
        }
        
        if (typeof(callback) !== 'undefined') {
            this.callback = callback;
        }
        
        if (!this.popup.length) {
            var popup = $('<div class="popup">').html('');
            $('body').append(popup);
            
            this.popup = popup;
            this.loading();
        }
        this.popup.css('display', 'none');
        
        // click outside to close
        $(".popup").click(function(e){
            if(e.target != this) return; // only continue if the target itself has been clicked
            // this section only processes if the .nav > li itself is clicked.
            Popup.hide();
        });
    }
    
    show() {
        this.popup.fadeIn();
        $('html').css('overflow', 'hidden');
    }
    
    hide() {
        this.popup.fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    loading() {
        this.popup.prepend(this.loadingHtml);
    }
    
    static hide() {
        $('.popup').fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    load(url) {
        var _this = this;
        
        if (typeof(url) !== 'undefined') {
            this.url = url;
        }
        
        this.loading();
        
        this.show();
        
        $.ajax({
            url: _this.url,
            type: 'GET',
            dataType: 'html',
        }).always(function(response) {
            _this.popup.html(response);
            
            if (typeof(_this.callback) !== 'undefined') {
                _this.callback();
            }
            
            // init js
            initJs(_this.popup);
            
            // set back button
            // back button
            if (typeof(_this.back) !== 'undefined') {
                _this.popup.find('.back').click( function() {                    
                    _this.back();
                });
            } else {
                _this.popup.find('.back').click( function() {                    
                    Popup.hide();
                });
            }
            
            // click close button
            _this.popup.find(".close").click(function(){
                Popup.hide();
            });
        });
    }
}
