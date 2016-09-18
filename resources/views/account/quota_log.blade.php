<h3 class="mt-0 mb-10"><i class="icon-stats-bars4"></i> {{ trans("messages.used_quota") }}</h3>
<div class="row quota_box">
    <div class="col-md-12">
        <div class="content-group-sm mt-20">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->customer->sendingQuota() }}/{{ Auth::user()->customer->maxQuota() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingQuotaUsage() }}
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.sending_quota') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->sendingQuotaUsage() }}%">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="content-group-sm">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->customer->listsCount() }}/{{ Auth::user()->customer->maxLists() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayListsUsage() }}
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.list') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->listsUsage() }}%">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="content-group-sm mt-20">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted progress-xxs">{{ Auth::user()->customer->campaignsCount() }}/{{ Auth::user()->customer->maxCampaigns() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayCampaignsUsage() }}
            </div>
            <h5 class="text-semibold mb-5 mt-0">{{ trans('messages.campaign') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->campaignsUsage() }}%">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="content-group-sm">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->customer->subscribersCount() }}/{{ Auth::user()->customer->maxSubscribers() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySubscribersUsage() }}
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.subscriber') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->subscribersUsage() }}%">
                </div>
            </div>
        </div>
    </div>
</div>