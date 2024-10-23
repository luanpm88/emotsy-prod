<?php

namespace Acelle\Library\Traits;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use Acelle\Model\LocalCustomer;

trait IsUserDb
{
    public function getLocalCustomer()
    {
        $masterCustomer = $this->customer;

        if (is_null($masterCustomer)) {
            throw new \Exception(get_class($this)."#{$this->id} does not have a master customer with id #{$this->id}");
        }

        $localCustomer = LocalCustomer::on($masterCustomer->db_connection)->where('id', $this->customer_id)->first();

        if (is_null($localCustomer)) {
            throw new \Exception(get_class($this)."#{$this->id} does not have a local customer with id #{$this->id}");
        }

        return $localCustomer;
    }
}