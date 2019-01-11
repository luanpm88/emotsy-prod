<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log as LaravelLog;

class Subscription extends \Acelle\Cashier\Models\Subscription
{
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function plan()
    {
        return $this->belongsTo('Acelle\Model\Plan', 'local_plan_id');
    }
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer', 'local_owner_id');
    }
}