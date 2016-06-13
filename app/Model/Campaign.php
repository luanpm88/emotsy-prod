<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_SENDING = 'sending';
    const STATUS_PAUSED = 'paused';
    const STATUS_DONE = 'done';

    public static function getPendingCampaigns()
    {
        return self::where('status', self::STATUS_PENDING)->get();
    }
}
