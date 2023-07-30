<?php

namespace Acelle\Model;

use Acelle\Model\Invoice;
use Acelle\Library\Facades\SubscriptionFacade;

class InvoiceChangePlan extends Invoice
{
    protected $table = 'invoices';

    public const TYPE_CHANGE_PLAN = 'change_plan';

    /**
     * Process invoice.
     *
     * @return void
     */
    public function process()
    {
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);

        // Xoá NEW renew invoice hiện tại nếu có
        if ($subscription->getItsOnlyUnpaidRenewInvoice()) {
            $subscription->getItsOnlyUnpaidRenewInvoice()->delete();
        }

        // change plan
        $newPlan = \Acelle\Model\PlanGeneral::findByUid($this->getMetadata()['new_plan_uid']);
        $subscription->changePlan($newPlan);

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
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_PAY_SUCCESS, $this->uid, [
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
        $data = $this->getMetadata();
        $plan = \Acelle\Model\PlanGeneral::findByUid($data['new_plan_uid']);
        $chargeInfo = trans('messages.bill.charge_now');

        return $this->getBillingInfoBase($chargeInfo, $plan);
    }

    public function checkoutAfterPayFailed($error)
    {
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_PAY_FAILED, $this->uid, [
            'amount' => $this->total(),
            'error' => $error,
        ]);
    }

    public function beforeCancel()
    {
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_CANCEL_INVOICE, $this->uid, [
            'amount' => $this->total(),
        ]);
    }

    public function refreshPrice()
    {
    }
}
