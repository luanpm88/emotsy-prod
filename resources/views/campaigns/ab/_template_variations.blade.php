<h3 class="mt-0">{{ trans('messages.campaign.ab.template_variations') }}</h3>

<div class="mb-4">
    <a href="" data-action="template-variation-box" for="template_variation_a" class="d-inline-block template-variation border p-3 rounded current me-4 text-dark">
        <div class="mb-3">
            <span><input checked id="template_variation_a" type="radio" name="template_variation" value="a" class="styled"></span>
            <span class="ms-1 fw-semibold">{{ trans('messages.campaign.ab.version_a') }}</span>
        </div>
        <div>
            <div class="border thumb-container">
                @if ($campaign->template)
                    <img style="width:100%;" class="template-variation-thumb" src="{{ $campaign->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                @else
                    <img style="width:100%;" class="template-variation-thumb" src="{{ url('images/icons/template-none.svg') }}" />
                @endif
            </div>
        </div>
    </a>

    <a href="" data-action="template-variation-box" for="template_variation_b" class="d-inline-block template-variation border p-3 rounded text-dark">
        <div class="mb-3">
            <span><input id="template_variation_b" type="radio" name="template_variation" value="a" class="styled"></span>
            <span class="ms-1 fw-semibold">{{ trans('messages.campaign.ab.version_b') }}</span>
        </div>
        <div>
            <div class="border thumb-container">
                @if ($campaign->template_b)
                    <img style="width:100%;" class="template-variation-thumb" src="{{ $campaign->getThumbUrl() }}?v={{ Carbon\Carbon::now() }}" />
                @else
                    <img style="width:100%;" class="template-variation-thumb" src="{{ url('images/icons/template-none.svg') }}" />
                @endif
            </div>
        </div>
    </a>
</div>

<hr>

<script>
    var templateVariationManager;

    $(function() {
        templateVariationManager = new TemplateVariationManager({
            boxes: document.querySelectorAll('[data-action="template-variation-box"]'),
        });
    });

    var TemplateVariationManager = class {
        constructor(options) {
            this.boxes = options.boxes;

            // event
            this.boxes.forEach(box => {
                box.querySelector('[name="template_variation"]').addEventListener('change', (e) => {
                    this.select(box);
                });
            });
        }

        select(box) {
            this.boxes.forEach(box => {
                box.classList.remove('current');
            });

            box.classList.add('current');
        }
    }
</script>