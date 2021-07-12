<ul class="nav nav-tabs nav-underline mb-1 campaign-steps" role="tablist">
    <li class="nav-item">
        <a class="nav-link fs-6 {{ $current == 1 ? "active" : "" }}" href="{{ action('CampaignController@recipients', $campaign->uid) }}">
            <span class="material-icons-outlined">
people
</span> {{ trans('messages.recipients') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 0 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 2 ? "active" : "" }}" href="{{ action('CampaignController@setup', $campaign->uid) }}">
            <span class="material-icons-outlined">
settings
</span> {{ trans('messages.setup') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 1 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 3 ? "active" : "" }}" href="{{ action('CampaignController@template', $campaign->uid) }}">
            <span class="material-icons-outlined">
auto_awesome_mosaic
</span> {{ trans('messages.template') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 2 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 4 ? "active" : "" }}" href="{{ action('CampaignController@schedule', $campaign->uid) }}">
            <span class="material-icons-outlined">
schedule
</span> {{ trans('messages.schedule') }}
        </a>
    </li>
    <li class="nav-item {{ $campaign->step() > 3 ? "" : "disabled" }}">
        <a class="nav-link fs-6 {{ $current == 5 ? "active" : "" }}" href="{{ action('CampaignController@confirm', $campaign->uid) }}">
            <span class="material-icons-outlined">
task_alt
</span> {{ trans('messages.confirm') }}
        </a>
    </li>
</ul>