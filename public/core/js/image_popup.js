class ImagePopup {
    constructor(element) {
        this.element = element;

        this.popup = new Popup({
            backdrop: true,
        });

        // events
        this.events();
    }

    getUrl() {
        return this.element.getAttribute('href');
    }

    events() {
        this.element.addEventListener('click', (e) => {
            e.preventDefault();
            
            this.popup.loadHtml(`
                <div class="modal-dialog shadow">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3">
                            <img width="100%" src="`+this.getUrl()+`" />
                        </div>
                    </div>
                </div>

            `);
        });
    }
}