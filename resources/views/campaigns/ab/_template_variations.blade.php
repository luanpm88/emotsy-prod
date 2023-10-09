<h3 class="mt-0 mb-3">{{ trans('messages.campaign.ab.template_variations') }}</h3>

<p>{{ trans('messages.campaign.ab.slit_templates.intro') }}</p>

<div class="mb-4 d-flex">
    <div class="me-4 template-variation {{ request()->version_b ? '' : 'current' }}">
        <a href="{{ action('CampaignController@template', [
            'uid' => $campaign->uid,
        ]) }}" data-action="template-variation-box" for="template_variation_a" class="thumb d-block border p-2 rounded text-dark">
            <div class="mb-2">
                <div class="d-flex align-items-center">
                    <span class="fw-semibold">{{ trans('messages.campaign.ab.version_a') }}</span>
                    <span class="ms-auto check-mark">
                        <span class="material-symbols-rounded text-success" style="transform:scale(2)">
                            task_alt
                        </span>
                    </span>
                </div>
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
        @if ($campaign->template_b)
            <div class="my-2 text-center version-action">
                <a href="" class="btn btn-default">{{ trans('messages.campaign.ab.template.copy_from_b') }}</a>
            </div>
        @endif
    </div>
    <div class="template-variation {{ request()->version_b ? 'current' : '' }}">
        <a href="{{ action('CampaignController@template', [
            'uid' => $campaign->uid,
            'version_b' => true,
        ]) }}" data-action="template-variation-box" for="template_variation_b" class="thumb d-block border p-2 rounded text-dark">
            <div class="mb-2">
                <div class="d-flex align-items-center">
                    <span class="fw-semibold">{{ trans('messages.campaign.ab.version_b') }}</span>
                    <span class="ms-auto check-mark">
                        <span class="material-symbols-rounded text-success" style="transform:scale(2)">
                            task_alt
                        </span>
                    </span>
                </div>
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
        @if ($campaign->template)
            <div class="my-2 text-center version-action">
                <a href="" class="btn btn-default">{{ trans('messages.campaign.ab.template.copy_from_a') }}</a>
            </div>
        @endif
    </div>
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