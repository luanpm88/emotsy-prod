class IframeModal {
    constructor() {
        this.modal = $('.iframe-modal');
        if (!this.modal.length) {
            var modal = $('<div>').html('<div class="iframe-modal"><iframe src=""></iframe></div>');
            $('body').append(modal);
            
            this.modal = modal;            
        }
        this.iframe = this.modal.find('iframe');
        this.modal.css('display', 'none');
    }
    
    show() {
        this.modal.fadeIn();
        $('html').css('overflow', 'hidden');
    }
    
    hide() {
        this.modal.fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    static hide() {
        $('.iframe-modal').parent().fadeOut();
        $('html').css('overflow', 'auto');
    }
    
    load(src) {        
        this.src = src;
        this.show();
        this.iframe.attr('src', this.src);
        
        console.log(this.modal);
    }
}
var GlobalIframeModal = IframeModal;