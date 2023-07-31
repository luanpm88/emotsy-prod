<?php

namespace Acelle\Model;

use Acelle\Model\Invoice;
use Acelle\Library\Facades\SubscriptionFacade;

class InvoiceChangePlan extends Invoice
{
    protected $table = 'invoices';

    public const TYPE_CHANGE_PLAN = 'change_plan';

    public function subscription()
    {
        return $this->belongsTo('Acelle\Model\Subscription');
    }

    public function newPlan()
    {
        return $this->belongsTo('Acelle\Model\Plan', 'new_plan_id');
    }

    /**
     * Process invoice.
     *
     * @return void
     */
    public function process()
    {
        // Xoá NEW renew invoice hiện tại nếu có
        if ($this->subscription->getItsOnlyUnpaidRenewInvoice()) {
            $this->subscription->getItsOnlyUnpaidRenewInvoice()->delete();
        }

        // change plan
        $this->subscription->changePlan($this->newPlan);

        // Different types of PLAN have different business here after payment for CHANGE PLANCE is successful
        // Let's add a process() method for Plan* too
        //
        // For example: PlanGeneral
        //     - refill sending credits
        //
        // For example: PlanSender
        //     - send an email to Admin? "Hey, your client has just successfully paid for SenderID CHANGE, from ID_OLD to ID_NEW, process it...."
        //
        // Others...
        //

        // Logging
        SubscriptionFacade::log($this->subscription, SubscriptionLog::TYPE_PAY_SUCCESS, $this->uid, [
            'amount' => $this->total(),
        ]);
    }

    /**
     * Get billing info.
     *
     * @return void
     */
    public function getBillingInfo()
    {
        $chargeInfo = trans('messages.bill.charge_now');

        return $this->getBillingInfoBase($chargeInfo, $this->newPlan);
    }

    public function checkoutAfterPayFailed($error)
    {
        SubscriptionFacade::log($this->subscription, SubscriptionLog::TYPE_PAY_FAILED, $this->uid, [
            'amount' => $this->total(),
            'error' => $error,
        ]);
    }

    public function beforeCancel()
    {
        SubscriptionFacade::log($this->subscription, SubscriptionLog::TYPE_CANCEL_INVOICE, $this->uid, [
            'amount' => $this->total(),
        ]);
    }

    public function refreshPrice()
    {
    }
}
