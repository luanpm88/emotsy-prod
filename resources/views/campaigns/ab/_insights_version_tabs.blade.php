<style>
    .btn-mode {
        background-color: transparent!important;
        border: none;
        border-radius: 3px!important;
    }
    .btn-mode:hover {
        background-color: rgba(184, 184, 184, 0)!important;
        box-shadow: none!important;
    }
    .mode-a .btn-mode[data-mode="mode-a"], .mode-b .btn-mode[data-mode="mode-b"] {
        pointer-events: none;
        background-color: #fff!important;
    }
    .mode-a .b-only, .mode-b .a-only {
        display: none;
    }
    body.mode-dark .btn-mode {
        background-color: transparent!important;
        border: none;
        border-radius: 3px!important;
    }
    body.mode-dark .btn-mode:hover {
        box-shadow: none!important;
        background-color: rgba(184, 184, 184, 0)!important;
    }
    body.mode-dark .mode-a .btn-mode[data-mode="mode-a"], body.mode-dark .mode-b .btn-mode[data-mode="mode-b"] {
        pointer-events: none;
        background-color: rgba(184, 184, 184, 0.2)!important;
    }
</style>

<div class="text-center {{ request()->version_b ? 'mode-b' : 'mode-a' }} d-flex justify-content-center">
    <div class="d-flex align-items-center">
        <div class="bg-light mode-group rounded p-1 border">
            <div class="btn-group">
                <a href="{{ $link_a }}" preview-action="mode" data-mode="mode-a" href="#" class="btn btn-default btn-mode me-2 border">
                    <span class="d-flex align-items-center">
                        <span class="material-symbols-rounded text-success me-2 a-only" style="transform:scale(1.5)">
                            task_alt
                        </span>
                        <span class="material-symbols-rounded me-2 b-only" style="transform:scale(1.5)">
                            radio_button_unchecked
                        </span>
                        <span>{{ trans('messages.campaign.ab.version_a') }}</span>
                    </span>
                </a>
                <a href="{{ $link_b }}" preview-action="mode" data-mode="mode-b" href="#" class="btn btn-default btn-mode">
                    <span class="d-flex align-items-center">
                        <span class="material-symbols-rounded text-success me-2 b-only" style="transform:scale(1.5)">
                            task_alt
                        </span>
                        <span class="material-symbols-rounded me-2 a-only" style="transform:scale(1.5)">
                            radio_button_unchecked
                        </span>
                        <span>{{ trans('messages.campaign.ab.version_b') }}</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>