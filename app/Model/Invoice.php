<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // wait status
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_CLAIMED = 'claimed';
    const STATUS_PAID = 'paid';

    // done status
    const STATUS_DONE = 'done';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REJECTED = 'rejected';

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
     * Invoice customer.
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Invoice items.
     */
    public function invoiceItems()
    {
        return $this->hasMany('Acelle\Model\InvoiceItem');
    }

    /**
     * Transactions.
     */
    public function transactions()
    {
        return $this->hasMany('Acelle\Model\Transaction');
    }

    /**
     * Last transaction.
     */
    public function lastTransaction()
    {
        return $this->transactions()
            ->orderBy('created_at', 'desc')
            ->first();
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
     * Set as paid.
     *
     * @return void
     */
    public function setPaid()
    {
        $this->status = Invoice::STATUS_PAID;
        $this->save();
    }

    /**
     * Set as rejected.
     *
     * @return void
     */
    public function setRejected($reason=null)
    {
        $this->status = Invoice::STATUS_REJECTED;
        $this->save();

        // reason
        if ($reason) {
            $this->updateMetadata([
                'reason' => trans('messages.invoice_rejected_reason', ['reason' => $reason]),
            ]);
        }
    }

    /**
     * Set as paid.
     *
     * @return void
     */
    public function setDone()
    {
        $this->status = Invoice::STATUS_DONE;
        $this->save();
    }

    /**
     * Set as canceled.
     *
     * @return void
     */
    public function setCanceled()
    {
        $this->status = Invoice::STATUS_CANCELED;
        $this->save();
    }

    /**
     * Set as pending.
     *
     * @return void
     */
    public function setClaimed()
    {
        $this->status = Invoice::STATUS_CLAIMED;
        $this->save();
    }

    /**
     * Set as pending.
     *
     * @return void
     */
    public function setNew()
    {
        $this->status = Invoice::STATUS_NEW;
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

    /**
     * formatted Total.
     *
     * @return void
     */
    public function formattedTotal()
    {
        return format_price($this->total(), $this->currency->format);
    }

    /**
     * Get metadata.
     *
     * @var object | collect
     */
    public function getMetadata($name=null)
    {
        if (!$this['metadata']) {
            return json_decode('{}', true);
        }

        $data = json_decode($this['metadata'], true);

        if ($name != null) {
            if (isset($data[$name])) {
                return $data[$name];
            } else {
                return null;
            }
        } else {
            return $data;
        }
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

    /**
     * Get type.
     *
     * @return void
     */
    public function getType()
    {
        return $this->invoiceItems()->first()->item_type;
    }

    /**
     * Check new.
     *
     * @return void
     */
    public function isNew()
    {
        return $this->status == self::STATUS_NEW;
    }

    /**
     * Check pending.
     *
     * @return void
     */
    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    /**
     * Check claimed.
     *
     * @return void
     */
    public function isClaimed()
    {
        return $this->status == self::STATUS_CLAIMED;
    }

    /**
     * Check paid.
     *
     * @return void
     */
    public function isPaid()
    {
        return $this->status == self::STATUS_PAID;
    }

    /**
     * Check done.
     *
     * @return void
     */
    public function isDone()
    {
        return $this->status == self::STATUS_DONE;
    }

    /**
     * Check rejected.
     *
     * @return void
     */
    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    /**
     * Get wait statuses.
     *
     * @return array
     */
    public static function waitStatuses()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_PENDING,
            self::STATUS_CLAIMED,
            self::STATUS_PAID,
        ];
    }

    /**
     * Check wait.
     *
     * @return void
     */
    public function isWait()
    {
        return in_array($this->status, self::waitStatuses());
    }

    /**
     * Get billing info.
     *
     * @return void
     */
    public function getBillingInfo()
    {
        // subscription init
        if ($this->getType() == \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_INIT) {
            $subscription = \Acelle\Cashier\Subscription::findByUid(
                $this->invoiceItems()
                    ->where('item_type', \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_INIT)
                    ->first()
                    ->item_id
            );
            return  [
                'title' => trans('messages.subscription_init.bill_details'),
                'description' => $this->description,
                'bill' => $this->invoiceItems()->billMapping(),
                'charge_info' => trans('messages.bill.charge_now'),
                'total' => format_price($this->total(), $this->currency->format),
            ];
        }

        // subscription init
        if ($this->getType() == \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_RENEW) {
            $subscription = \Acelle\Cashier\Subscription::findByUid(
                $this->invoiceItems()
                    ->where('item_type', \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_RENEW)
                    ->first()
                    ->item_id
            );
            return  [
                'title' => trans('messages.subscription_renew.bill_details'),
                'description' => $this->description,
                'bill' => $this->invoiceItems()->billMapping(),
                'charge_info' => $this->customer->paymentCanAutoCharge() ? trans('messages.bill.auto_charge', ['date' => $subscription->nextPeriod()]) : trans('messages.bill.charge_before', ['date' => $subscription->nextPeriod()]),
                'total' => format_price($this->total(), $this->currency->format),
            ];
        }

        // subscription change plan
        if ($this->getType() == \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_CHANGE_PLAN) {
            $subscription = \Acelle\Cashier\Subscription::findByUid(
                $this->invoiceItems()
                    ->where('item_type', \Acelle\Model\InvoiceItem::TYPE_SUBSCRIPTION_CHANGE_PLAN)
                    ->first()
                    ->item_id
            );

            // plan
            $data = $this->invoiceItems()->first()->getMetadata();
            $plan = \Acelle\Model\Plan::findByUid($data['plan_uid']);

            return  [
                'title' => trans('messages.subscription_change_plan.bill_details'),
                'description' => $this->description,
                'bill' => $this->invoiceItems()->billMapping(),
                'charge_info' => '',
                'total' => format_price($this->total(), $this->currency->format),
            ];
        }

        return 'unknown';
    }

    /**
     * Add transactions.
     *
     * @return array
     */
    public function addLog($type, $data, $transaction_id=null)
    {
        $log = new SubscriptionLog();
        $log->subscription_id = $this->id;
        $log->type = $type;
        $log->transaction_id = $transaction_id;
        $log->save();

        if (isset($data)) {
            $log->updateData($data);
        }

        return $log;
    }

    /**
     * Add transaction.
     *
     * @return array
     */
    public function addTransaction($data=[])
    {
        $transaction = new \Acelle\Model\Transaction();
        $transaction->invoice_id = $this->id;
        $transaction->amount = $this->formattedTotal();
        $transaction->message = $data['message'];
        $transaction->method = $this->customer->getPaymentMethod() ? $this->customer->getPaymentMethod()['method'] : trans('messages.payment_manual');
        $transaction->error = isset($data['error']) ? $data['error'] : null;
        $transaction->status = isset($data['status']) ? $data['status'] : \Acelle\Model\Transaction::STATUS_PENDING;
        $transaction->save();

        // metadata
        if (isset($data['metadata'])) {
            $transaction->updateData($data['metadata']);
        }

        return $transaction;
    }
}
