<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    const TYPE_SUBSCRIPTION_INIT = 'subscription_init';

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $item->uid = uniqid();
        });
    }

    /**
     * Total amount.
     *
     * @return void
     */
    public function total()
    {
        return $this->amount + $this->tax - $this->discount;
    }
}
