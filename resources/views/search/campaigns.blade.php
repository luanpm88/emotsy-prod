@if ($campaigns->total())
    <a href="javascript:;" class="search-head border-bottom d-block">
        <label class="fw-600">
            <span class="material-icons-outlined me-1">
                dynamic_feed
                </span> Campaigns
        </label>
    </a>
    @foreach($campaigns as $campaign)
        <a href="{{ action('CampaignController@show', $campaign->uid) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <label class="fw-600">
                        {{ $campaign->name }}
                    </label>
                    <p class="desc text-muted mt-1 mb-0">
                        <span class="fw-600">10</span>/<span class="fw-600">513</span> sent  ·  
                        <span class="fw-600"> 66.09% </span> open rate    ·  
                        <span class="fw-600">70.39%</span>click rate</p>
                </div>
            </div>
                
        </a>
    @endforeach
@endif