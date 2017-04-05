<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Paid status
	const ACTION_PAID = 'paid';
	const ACTION_UNPAID = 'unpaid';

    // Status
    const STATUS_SUCCESS = 'success';

    public $fillable = ['item_number','transaction_id','currency_code','payment_status'];

    public function paymentMethod()
    {
        return $this->belongsTo('Acelle\Model\PaymentMethod');
    }

    public function subscription()
    {
        return $this->belongsTo('Acelle\Model\Subscription');
    }

    /**
     * Get payment method name.
     *
     * @return string
     */
    public function getPaymentMethodName()
    {
        return $this->payment_method_name ? $this->payment_method_name : trans('messages.payment_manual');
    }

    /**
     * Get payment method name.
     *
     * @return string
     */
    public function getOrderID()
    {
        return $this->order_id;
    }

    /**
     * Get errors messages.
     *
     * @return string
     */
    public function getErrorMessages()
    {
        $messages = [];
        $result = unserialize($this->data);
        if (count($result->errors->deepAll()) > 0) {
            foreach ($result->errors->deepAll() AS $error) {
                $messages[] = $error->code . ": " . $error->message;
            }
        }

        return $messages;
    }
}
