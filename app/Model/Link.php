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
    
    public function lastClick($user=null)
    {
        $query = ClickLog::select("click_logs.*")
            ->where('click_logs.url', "=", $this->url);
        
        if(isset($user)) {
            $query = $query->join('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
                ->join('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id')
                ->where('campaigns.user_id', "=", $user->id);
        }
        
        $query = $query->orderBy("created_at", "desc")->first();
        
        return $query;
    }
}
