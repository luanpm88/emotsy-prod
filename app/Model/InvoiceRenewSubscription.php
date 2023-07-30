<?php

namespace Acelle\Model;

use Acelle\Model\Invoice;
use Acelle\Library\Facades\SubscriptionFacade;

class InvoiceRenewSubscription extends Invoice
{
    protected $table = 'invoices';

    public const TYPE_RENEW_SUBSCRIPTION = 'renew_subscription';

    /**
     * Process invoice.
     *
     * @return void
     */
    public function process()
    {
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);

        // Xoá NEW change plan invoice hiện tại nếu có
        if ($subscription->getItsOnlyUnpaidChangePlanInvoice()) {
            $subscription->getItsOnlyUnpaidChangePlanInvoice()->delete();
        }

        /// renew
        $subscription->renew();

        // Different types of PLAN have different business here after payment for RENEW SUBSCRIPTION is successful
        // Let's add a process() method for Plan* too
        //
        // For example: PlanGeneral
        //     - refill default sending credits
        //
        // For example: PlanSender
        //     - send an email to Admin? "Hey, one of your client has just successfully paid for SenderID RENEW, please manually RENEW it on Twilio...."
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
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        $chargeInfo = trans('messages.bill.charge_before', [
            'date' => $this->customer->formatDateTime($subscription->current_period_ends_at, 'datetime_full'),
        ]);
        $plan = $subscription->planGeneral;

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
        if ($this->isPaid()) {
            throw new \Exception("Can not change paid invoice price!");
        }

        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        $invoiceItem = $this->invoiceItems()->first(); // subscription general plan always has 1 invoice item (design)
        if ($invoiceItem->amount != $subscription->planGeneral->getPrice()) {
            $invoiceItem->amount = $subscription->planGeneral->getPrice();
            $invoiceItem->save();
        }
    }

    public function getSubscription() {
        return Subscription::findByUid($this->getMetadata()['subscription_uid']);
    }
}
