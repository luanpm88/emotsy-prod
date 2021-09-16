var Popup_{{ $template->uid }} = {
    node: null,
    css: null,
    iframe: null,

    init: function() {
        var _this = this;

        var div = document.createElement("div");
        div.innerHTML = `
            <div class="acelle-popup-container">
                <div class="acelle-popup-container-scroll">
                    <iframe id="Popup{{ $template->uid }}Frame" src="{{ action('PopupController@content', [
                        'template_uid' => $template->uid,
                    ]) }}"></iframe>   
                    
                </div> 
                <div id="Popup{{ $template->uid }}Close" class="acelle-popup-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="#000" d="M7.05022 7.05028C6.65969 7.4408 6.65969 8.07397 7.05022 8.46449L10.5858 12L7.05023 15.5356C6.6597 15.9261 6.6597 16.5593 7.05023 16.9498C7.44075 17.3403 8.07392 17.3403 8.46444 16.9498L12 13.4142L15.5355 16.9498C15.926 17.3403 16.5592 17.3403 16.9497 16.9498C17.3402 16.5592 17.3402 15.9261 16.9497 15.5356L13.4142 12L16.9497 8.46449C17.3402 8.07397 17.3402 7.4408 16.9497 7.05028C16.5592 6.65976 15.926 6.65976 15.5355 7.05028L12 10.5858L8.46443 7.05028C8.07391 6.65975 7.44074 6.65975 7.05022 7.05028Z"/></svg>
                </div>
            </div>`;
        div.classList.add("acelle-popup");
        div.id = 'Popup_{{ $template->uid }}';
        document.body.appendChild(div);

        this.node = div;
        this.iframe = document.getElementById('Popup{{ $template->uid }}Frame');

        this.loadCss();

        window.addEventListener("message", function(event) {
            _this.adjustIframeSize(event.data);
        });

        document.getElementById('Popup{{ $template->uid }}Close').addEventListener("click", function() {
            _this.hide();
        });
    },

    adjustIframeSize: function(height) {
        this.iframe.style.height = height + 'px';
    },

    loadCss: function() {
        if (this.css == null) {
            var head  = document.getElementsByTagName('head')[0];
            var link  = document.createElement('link');
            link.rel  = 'stylesheet';
            link.type = 'text/css';
            link.href = '{{ url('core/css/form_popup.css') }}';
            link.media = 'all';
            head.appendChild(link);

            this.css = link;
        }
    },
    
    show: function() {
        document.body.classList.add("acelle-popup-open");
    },

    hide: function() {
        document.body.classList.remove("acelle-popup-open");
    }
};

document.addEventListener("DOMContentLoaded", function(event) {
    Popup_{{ $template->uid }}.init();
    Popup_{{ $template->uid }}.show();
});