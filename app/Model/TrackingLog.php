<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    const STATUS_SENT = 'sent';
    const STATUS_BOUNCED = 'bounce';
    const STATUS_FEEDBACK_ABUSE = 'feedback-abuse';
    const STATUS_FEEDBACK_SPAM = 'feedback-spam';
}
