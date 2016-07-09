<h3 class="mt-0 mb-0">{{ trans("messages.used_quota") }}</h3>
<div class="row">
    <div class="col-md-12">
        <div class="content-group-sm">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->sendingQuota() }}/{{ Auth::user()->maxQuota() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->sendingQuotaUsage() }}%
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.list') }}</h5>
            <div class="progress">
                <div class="progress-bar bg-info-600" style="width: {{ Auth::user()->sendingQuotaUsage() }}%">
                </div>
            </div>
        </div>
        <hr>
        <div class="content-group-sm">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->listsCount() }}/{{ Auth::user()->maxLists() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->listsUsage() }}%
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.list') }}</h5>
            <div class="progress">
                <div class="progress-bar bg-info-600" style="width: Auth::user()->listsUsage()%">
                </div>
            </div>
        </div>
        <hr>
        <div class="content-group-sm">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->campaignsCount() }}/{{ Auth::user()->maxCampaigns() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->campaignsUsage() }}%
            </div>
            <h5 class="text-semibold mb-5 mt-0">{{ trans('messages.campaign') }}</h5>
            <div class="progress">
                <div class="progress-bar bg-info-600" style="width: {{ Auth::user()->campaignsUsage() }}%">
                </div>
            </div>
        </div>
        <hr>
        <div class="content-group-sm">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->subscribersCount() }}/{{ Auth::user()->maxSubscribers() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->subscribersUsage() }}%
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.subscriber') }}</h5>
            <div class="progress">
                <div class="progress-bar bg-info-600" style="width: {{ Auth::user()->subscribersUsage() }}%">
                </div>
            </div>
        </div>
    </div>
</div>