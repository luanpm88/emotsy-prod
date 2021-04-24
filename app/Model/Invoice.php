<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // Plan status
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';

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
     * Invoice currency.
     */
    public function currency()
    {
        return $this->belongsTo('Acelle\Model\Currency');
    }

    /**
     * Invoice items.
     */
    public function invoiceItems()
    {
        return $this->hasMany('Acelle\Model\InvoiceItem');
    }

    /**
     * Set as pending.
     *
     * @return void
     */
    public function setPending()
    {
        $this->status = Invoice::STATUS_PENDING;
        $this->save();
    }

    /**
     * Total items.
     *
     * @return void
     */
    public function total()
    {
        $total = 0;

        foreach($this->invoiceItems as $item) {
            $total += $item->total();
        }

        return $total;
    }
}
