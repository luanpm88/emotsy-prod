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
}
