<h3 class="mt-0 mb-0"><i class="icon-stats-bars4"></i> {{ trans("messages.used_quota") }}</h3>
<div class="row quota_box">
    <div class="col-md-12">
        <div class="content-group-sm mt-20">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted">{{ Auth::user()->customer->getSendingQuotaUsage() }}/{{ (Auth::user()->customer->getSendingQuota() == -1) ? '∞' : Auth::user()->customer->getSendingQuota() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingQuotaUsage() }}
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.sending_quota') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->getSendingQuotaUsagePercentage() }}%">
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
                <span class="text-muted">{{ Auth::user()->customer->readCache('SubscriberCount') }}/{{ Auth::user()->customer->maxSubscribers() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySubscribersUsage() }}
            </div>
            <h5 class="text-semibold mb-5">{{ trans('messages.subscriber') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->readCache('SubscriberUsage') }}%">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="content-group-sm mt-20">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted progress-xxs">{{ Auth::user()->customer->automationsCount() }}/{{ Auth::user()->customer->maxAutomations() }}</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displayAutomationsUsage() }}
            </div>
            <h5 class="text-semibold mb-5 mt-0">{{ trans('messages.automation') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->automationsUsage() }}%">
                </div>
            </div>
        </div>
    </div>
        
    <div class="col-md-12">
        <div class="content-group-sm mt-20">
            <div class="pull-right text-teal-800 text-semibold">
                <span class="text-muted progress-xxs">{{ round(Auth::user()->customer->totalUploadSize(),2) }}/{{ Auth::user()->customer->maxTotalUploadSize() }} (MB)</span>
                &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->totalUploadSizeUsage() }}%
            </div>
            <h5 class="text-semibold mb-5 mt-0">{{ trans('messages.total_upload_size') }}</h5>
            <div class="progress progress-xxs">
                <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->totalUploadSizeUsage() }}%">
                </div>
            </div>
        </div>
    </div>
    
    @can ("create", new Acelle\Model\SendingServer())
        <div class="col-md-12">
            <div class="content-group-sm">
                <div class="pull-right text-teal-800 text-semibold">
                    <span class="text-muted">{{ Auth::user()->customer->sendingServersCount() }}/{{ Auth::user()->customer->maxSendingServers() }}</span>
                    &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingServersUsage() }}
                </div>
                <h5 class="text-semibold mb-5">{{ trans('messages.sending_server') }}</h5>
                <div class="progress progress-xxs">
                    <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->sendingServersUsage() }}%">
                    </div>
                </div>
            </div>
        </div>
    @endcan
    
    @can ("create", new Acelle\Model\SendingDomain())
        <div class="col-md-12">
            <div class="content-group-sm">
                <div class="pull-right text-teal-800 text-semibold">
                    <span class="text-muted">{{ Auth::user()->customer->sendingDomainsCount() }}/{{ Auth::user()->customer->maxSendingDomains() }}</span>
                    &nbsp;&nbsp;&nbsp;{{ Auth::user()->customer->displaySendingDomainsUsage() }}
                </div>
                <h5 class="text-semibold mb-5">{{ trans('messages.sending_domain') }}</h5>
                <div class="progress progress-xxs">
                    <div class="progress-bar bg-warning" style="width: {{ Auth::user()->customer->sendingDomainsUsage() }}%">
                    </div>
                </div>
            </div>
        </div>
    @endcan
</div>
