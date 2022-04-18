<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class CampaignWebhook extends Model
{
    use HasFactory;
    use HasUid;

    public const TYPE_OPEN = 'open';
    public const TYPE_CLICK = 'click';
    public const TYPE_UNSUBSCRIBE = 'unsubscribe';

    public function campaignLink()
    {
        return $this->belongsTo('Acelle\Model\CampaignLink');
    }

    public function campaign()
    {
        return $this->belongsTo('Acelle\Model\Campaign');
    }

    public function createFromArray($params)
    {
        // init rule
        $rules = [
            'endpoint' => 'required|url',
        ];

        // fill
        $this->type = $params['type'];
        $this->endpoint = $params['endpoint'];

        if ($params['type'] == self::TYPE_CLICK) {
            // fill campaign link id
            if (isset($params['campaign_link_id'])) {
                $this->campaign_link_id = $params['campaign_link_id'];
            }

            // rule update
            $rules['campaign_link_id'] = 'required';
        }

        // make validator
        $validator = \Validator::make($params, $rules);

        // redirect if fails
        if ($validator->fails()) {
            return [$this, $validator];
        }

        $this->save();

        return [$this, $validator];
    }

    public function updateFromArray($params)
    {
        // init rule
        $rules = [
            'endpoint' => 'required|url',
        ];

        // fill
        $this->endpoint = $params['endpoint'];

        // make validator
        $validator = \Validator::make($params, $rules);

        // redirect if fails
        if ($validator->fails()) {
            return [$this, $validator];
        }

        $this->save();

        return [$this, $validator];
    }
}
