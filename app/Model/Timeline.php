<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class Timeline extends Model
{
    use HasUid;

    protected $fillable = ['automation2_id', 'subscriber_id', 'auto_trigger_id', 'activity', 'activity_type'];

    public const TYPE_ADDED = 'added';
    public const TYPE_OPT_IN = 'opt_in';
    public const TYPE_OPEN_CAMPAIGN_EMAIL = 'open_campaign_email';
    public const TYPE_CLICK_CAMPAIGN_EMAIL = 'open_campaign_email';
    public const TYPE_OPEN_AUTOMATION_EMAIL = 'open_campaign_email';
    public const TYPE_CLICK_AUTOMATION_EMAIL = 'open_campaign_email';

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function subscriber()
    {
        return $this->belongsTo('Acelle\Model\Subscriber');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function newDefault($subscriber, $type)
    {
        $timeline = new static();

        $timeline->subscriber_id = $subscriber->id;
        $timeline->type = $type;

        return $timeline;
    }

    public static function recordAdded($subscriber, $customer)
    {
        $timeline = static::newDefault($subscriber, static::TYPE_ADDED);

        $timeline->customer_id = $customer->id;

        $timeline->save();

        return $timeline;
    }

    public static function recordOpenCampaignEmail($subscriber, $campaign)
    {
    }
}
