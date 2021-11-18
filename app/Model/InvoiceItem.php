<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class InvoiceItem extends Model
{
    use HasUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'item_type', 'amount', 'title', 'description', 'vat'
    ];

    /**
     * Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo('Acelle\Model\Invoice');
    }

    /**
     * Tax amount.
     *
     * @return void
     */
    public function getTax()
    {
        if ($this->invoice->billing_country_id) {
            $country = \Acelle\Model\Country::find($this->invoice->billing_country_id);
            $tax = \Acelle\Model\Setting::getTaxByCountry($country);
        } else {
            $tax = \Acelle\Model\Setting::getTaxByCountry(null);
        }

        return ($this->subTotal() * ($tax/100));
    }

    public function subTotal()
    {
        return $this->amount - $this->discount;
    }

    /**
     * Total amount.
     *
     * @return void
     */
    public function total()
    {
        return $this->subTotal() + $this->getTax();
    }
}
