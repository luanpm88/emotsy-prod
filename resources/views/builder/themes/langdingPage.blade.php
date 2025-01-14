<script>   
    // Header widget
    class LandingPageNavbar extends Widget {
        getHtmlId() {
            return "LandingPageNavbar";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/LandingPageNavbar.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Header</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <nav class="navbar navbar-expand-lg">
                    <a builder-element class="navbar-brand" href="#">BrandLogo</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a builder-element class="nav-link" href="#features">Features</a></li>
                            <li class="nav-item"><a builder-element class="nav-link" href="#pricing">Pricing</a></li>
                            <li class="nav-item"><a builder-element class="nav-link" href="#team">Team</a></li>
                            <li class="nav-item"><a builder-element class="nav-link" href="#contact">Contact</a></li>
                        </ul>
                    </div>
                </nav>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    class WidgetManager {
        static init() {
            return [
                new LandingPageNavbar,
            ];
        }
    }
</script>