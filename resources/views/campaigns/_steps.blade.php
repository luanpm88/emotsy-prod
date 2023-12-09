<ul class="nav nav-tabs nav-underline mb-0 campaign-steps border-bottom-0" role="tablist">
    <li class="nav-item">
        <a class="nav-link pb-1 fs-7 bg-transparent {{ $current == 1 ? "active" : "" }} me-0" href="{{ action('CampaignController@recipients', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}">
            <span class="material-symbols-rounded">people</span> {{ trans('messages.recipients') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 0 ? "" : "disabled" }}">
        <span class="material-symbols-rounded mx-3 text-muted2" style="font-size:11px;">arrow_forward_ios</span>
        <a class="nav-link pb-1 fs-7 bg-transparent {{ $current == 2 ? "active" : "" }} me-0" href="{{ action('CampaignController@setup', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}">
            <span class="material-symbols-rounded">settings</span> {{ trans('messages.setup') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 1 ? "" : "disabled" }}">
        <span class="material-symbols-rounded mx-3 text-muted2" style="font-size:11px;">arrow_forward_ios</span>
        <a class="nav-link pb-1 fs-7 bg-transparent {{ $current == 3 ? "active" : "" }} me-0" href="{{ action('CampaignController@template', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}">
            <span class="material-symbols-rounded">auto_awesome_mosaic</span> {{ trans('messages.template') }}
        </a>
    </li>
    @if ($campaign->isABTest())
        <li class="nav-item {{ $campaign->step() > 2 ? "" : "disabled" }}">
            <span class="material-symbols-rounded mx-3 text-muted2" style="font-size:11px;">arrow_forward_ios</span>
            <a class="nav-link pb-1 fs-7 bg-transparent {{ $current == 'ab' ? "active" : "" }} me-0" href="{{ action('CampaignController@abSplitOptions', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}">
                <span class="material-symbols-rounded">splitscreen_right</span> {{ trans('messages.campaign.ab.split_options') }}
            </a>
        </li>
    @endif
    @if (!$campaign->isABTest())
        <li class="nav-item {{ $campaign->step() > 2 ? "" : "disabled" }}">
            <span class="material-symbols-rounded mx-3 text-muted2" style="font-size:11px;">arrow_forward_ios</span>
            <a class="nav-link pb-1 fs-7 bg-transparent {{ $current == 4 ? "active" : "" }} me-0" href="{{ action('CampaignController@schedule', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}">
                <span class="material-symbols-rounded">schedule</span> {{ trans('messages.schedule') }}
            </a>
        </li>
    @endif
    <li class="nav-item {{ $campaign->step() > 3 ? "" : "disabled" }} me-0">
        <span class="material-symbols-rounded mx-3 text-muted2" style="font-size:11px;">arrow_forward_ios</span>
        <a class="nav-link pb-1 fs-7 bg-transparent {{ $current == 5 ? "active" : "" }}" href="{{ action('CampaignController@confirm', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}">
            <span class="material-symbols-rounded">task_alt</span> {{ trans('messages.confirm') }}
        </a>
    </li>
</ul>