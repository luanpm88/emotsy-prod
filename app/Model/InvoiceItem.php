<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    const TYPE_SUBSCRIPTION_INIT = 'subscription_init';
    const TYPE_SUBSCRIPTION_RENEW = 'subscription_renew';
    const TYPE_SUBSCRIPTION_CHANGE_PLAN = 'subscription_change_plan';

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
     * Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo('Acelle\Model\Invoice');
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

    /**
     * Map to bill list.
     *
     * @return void
     */
    public function scopeBillMapping($query)
    {
        return $query->get()->map(function($item) {
            switch ($item->item_type) {
                case \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_INIT:
                case \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_RENEW:
                    $subscription = \Acelle\Cashier\Subscription::findByUid($item->item_id);
                    return [
                        'name' => $subscription->plan->name,
                        'desc' => view('plans._bill_desc', ['plan' => $subscription->plan]),
                        'price' => format_price($item->amount, $item->invoice->currency->format),
                        'tax' => format_price($item->tax, $item->invoice->currency->format),
                        'discount' => format_price($item->discount, $item->invoice->currency->format),
                    ];
                    break;
                case \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_CHANGE_PLAN:
                    $subscription = \Acelle\Cashier\Subscription::findByUid($item->item_id);
                    $data = $item->getMetadata();
                    $plan = \Acelle\Model\Plan::findByUid($data['plan_uid']);
                    return [
                        'name' => trans('messages.change_to_plan', ['plan' => $plan->name]),
                        'desc' => view('plans._bill_desc', ['plan' => $plan, 'endsAt' => \Carbon\Carbon::parse($data['endsAt'])]),
                        'price' => format_price($item->amount, $item->invoice->currency->format),
                        'tax' => format_price($item->tax, $item->invoice->currency->format),
                        'discount' => format_price($item->discount, $item->invoice->currency->format),
                    ];
                    break;
                default:
                    return [
                        'name' => $item->item_id,
                        'price' => format_price($item->amount, $item->invoice->currency->code),
                    ];
            }
        })->toArray();
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function getMetadata()
    {
        if (!$this['metadata']) {
            return json_decode('{}', true);
        }

        return json_decode($this['metadata'], true);
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function updateMetadata($data)
    {
        $metadata = (object) array_merge((array) $this->getMetadata(), $data);
        $this['metadata'] = json_encode($metadata);

        $this->save();
    }
}
