<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class PlansSendingServer extends Model
{
    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }
}
