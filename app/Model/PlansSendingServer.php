<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class PlansSendingServer extends Model
{
    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }
    
    public function showFitness()
    {
        $sum = PlansSendingServer::where('plan_id', '=', $this->plan_id)
            ->sum('fitness');
        
        return round(($this->fitness/$sum)*100);
    }
}
