class Popup {
    constructor(options) {
        this.init(options);
    }

    init(options) {
        var _this = this;
        this.id = '_' + Math.random().toString(36).substr(2, 9);
        this.options = {};
        this.popup = $('.popup[id='+this.id+']');
        this.data = {};
        this.backs = [];
        this.initHtml = `
            <div class="modal" id="`+this.id+`" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                        
                        </div>
                    </div>
                </div>
            </div>
        `;

        // options
        if (typeof(options) !== 'undefined') {
            this.options = options;
        }
        
        if (!this.popup.length) {
            var popup = $(this.initHtml);
            $('body').append(popup);
            
            this.popup = popup;
        }
        
        this.modal = new bootstrap.Modal(document.getElementById(this.id), {
            backdrop: 'static'
        });

        this.popup[0].addEventListener('hidden.bs.modal', function (event) {
            _this.reset();
        })
    }

    reset() {
        this.popup.remove();
        this.init(this.options);
    }
    
    show() {
        this.modal.show();
    }
    
    hide() {
        this.modal.hide();

        // onclose
        if (this.options.onclose != null) {
            this.options.onclose();
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

    mask() {
        this.popup.addClass('popup-loading');
        this.popup.find('.modal-dialog').append(`
            <div class="progress popup-progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
        `);
    }

    unmask() {
        this.popup.removeClass('popup-loading');
        this.popup.find('.popup-progress').remove
    }
    
    load(options) {
        var _this = this;
        var newOptions = {};

        if (typeof(options) == 'string') {
            newOptions.url = options;
        } else {
            // update options            
            if (typeof(options) !== 'undefined') {
                newOptions = options;
            }
        }
        
        // save current options
        if (typeof(newOptions.noHistory) == 'undefined' && typeof(newOptions.url) !== 'undefined' && newOptions.url != _this.options.url) {
            this.backs.push(_this.options);
        }

        // update options            
        _this.options = $.extend({}, _this.options, newOptions);
        
        // show popup
        _this.show();

        // effect
        _this.mask();

        // return;

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
        }).always(function() {
            // effect
            _this.unmask();
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