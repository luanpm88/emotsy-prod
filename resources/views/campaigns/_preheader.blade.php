<div id="PreheaderBox" class="sub-section">   
    
</div>

<script>
    var Campaign_Preheader = class {
        constructor() {
            this.box = new Box($('#PreheaderBox'), '{{ action('CampaignController@preheader', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}');
            this.box.load();
        }
    }

    $(function() {
        campaign_Preheader = new Campaign_Preheader();
    });
</script>