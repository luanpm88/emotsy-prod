<ul class="nav nav-tabs nav-underline mb-1" role="tablist">
    <li class="nav-item">
        <a class="nav-link fs-6 {{ $current == 1 ? "active" : "" }}" href="{{ action('CampaignController@recipients', $campaign->uid) }}">
            <i class="icon-users4"></i> {{ trans('messages.recipients') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 0 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 2 ? "active" : "" }}" href="{{ action('CampaignController@setup', $campaign->uid) }}">
            <i class="icon-gear"></i> {{ trans('messages.setup') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 1 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 3 ? "active" : "" }}" href="{{ action('CampaignController@template', $campaign->uid) }}">
            <i class="icon-magazine"></i> {{ trans('messages.template') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 2 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 4 ? "active" : "" }}" href="{{ action('CampaignController@schedule', $campaign->uid) }}">
            <i class="icon-alarm-check"></i> {{ trans('messages.schedule') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 3 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 5 ? "active" : "" }}" href="{{ action('CampaignController@confirm', $campaign->uid) }}">
            <i class="icon-checkmark4"></i> {{ trans('messages.confirm') }}
        </a>
    </li>
</ul>