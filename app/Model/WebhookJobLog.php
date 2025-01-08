<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class WebhookJobLog extends Model
{
    use HasUid;

    public function getRequestDetails()
    {
        return json_decode($this->request_details, true);
    }
}