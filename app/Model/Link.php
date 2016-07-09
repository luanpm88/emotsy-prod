<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * Get the campaigns for link.
     */
    public function campaigns()
    {
        return $this->belongsToMany('Mailixa\Model\Campaign', 'campaign_links');
    }
    
    public function clicks($user=null, $campaign=null)
    {
        $query = ClickLog::select("click_logs.*")
            ->where('click_logs.url', "=", $this->url);
        
        if(isset($user)) {
            $query = $query->join('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
                ->join('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id')
                ->where('campaigns.user_id', "=", $user->id);
        }
        
        if(isset($campaign)) {
            $query = $query->join('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
                ->where('tracking_logs.campaign_id', "=", $campaign->id);
        }
        
        return $query;
    }
    
    public function lastClick($user=null, $campaign=null)
    {        
        $query = $this->clicks($user, $campaign)->orderBy("created_at", "desc")->first();
        
        return $query;
    }
}
