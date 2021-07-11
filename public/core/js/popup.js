class Popup {
    constructor(options) {
        var _this = this;
        this.id = '_' + Math.random().toString(36).substr(2, 9);
        this.options = {};
        this.popup = $('.popup[id='+this.id+']');
        this.data = {};
        this.backs = [];

        // options
        if (typeof(options) !== 'undefined') {
            this.options = options;
        }
        
        if (!this.popup.length) {
            var popup = $(`
                <div class="modal" id="`+this.id+`" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Modal body text goes here.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                        </div>
                    </div>
                </div>`
            );
            $('body').append(popup);
            
            this.popup = popup;
        }
        
        this.modal = new bootstrap.Modal(document.getElementById(this.id), {
            backdrop: 'static'
        });

        this.popup[0].addEventListener('hidden.bs.modal', function (event) {
            _this.remove();
        })

        // onclose popup
        if(this.options.onclose != null) {
            this.onclose = this.options.onclose;
        }
    }

    remove() {
        this.modal.dispose();
        this.popup.remove();
    }
    
    show() {
        this.modal.show();
    }
    
    hide() {
        this.modal.hide();

        // onclose
        if (this.onclose != null) {
            this.onclose();
        }

        // clear backs
        this.backs = [];

        if (typeof(this.onHide) !== 'undefined') {
            this.onHide();
        }
    }

    loaded() {
        // apply js for new content
        this.applyJs();
        
        // remove loading effects
        this.popup.find('.popup-loading').remove();        
        this.popup.removeClass('popup-is-loading');
    }
    
    static hide() {
        $('.popup').fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    applyJs() {
        var _this = this;
        
        // init js
        initJs(_this.popup);
        
        // back button
        if (_this.backs.length > 1) {
            _this.popup.find('.back').on('click', function() {
                var backing = _this.backs.pop();
                backing = $.extend({}, backing, {noHistory: true});

                _this.load(backing);
            });
        } else {
            _this.popup.find('.back').on('click', function() {                    
                _this.hide();
            });
        }
        
        // click close button
        _this.popup.find(".close").click(function(){
            _this.hide();
        });
    }
    
    load(options) {
        var _this = this;
        var newOptions = {};

        // update options            
        if (typeof(options) !== 'undefined') {
            newOptions = options;
        }
        
        // save current options
        if (typeof(newOptions.noHistory) == 'undefined' && typeof(newOptions.url) !== 'undefined' && newOptions.url != _this.options.url) {
            this.backs.push(_this.options);
        }

        // update options            
        _this.options = $.extend({}, _this.options, newOptions);
        
        // show popup
        _this.show();
        
        // load from url
        $.ajax({
            url: _this.options.url,
            type: 'GET',
            dataType: 'html',
            data: _this.options.data,
        }).done(function(response) {
            _this.popup.html(response);
            
            if (typeof(_this.options.callback) !== 'undefined') {
                _this.options.callback();
            }
            
            // after load
            _this.loaded();

        }).fail(function(jqXHR, textStatus, errorThrown){
            // for debugging
            alert(errorThrown);
        });
    }
    
    loadHtml(html) {
        var _this = this;

        // show popup
        _this.show();
        
        _this.popup.html(html);
        
        // after load
        _this.loaded();
    }
}