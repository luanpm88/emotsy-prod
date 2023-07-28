<?php

namespace Acelle\Model;

use Acelle\Model\Invoice;
use Acelle\Library\Facades\SubscriptionFacade;

class InvoiceNewSubscription extends Invoice
{
    protected $table = 'invoices';

    public const TYPE_NEW_SUBSCRIPTION = 'new_subscription';

    /**
     * Process invoice.
     *
     * @return void
     */
    public function process()
    {
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);

        // activate sub
        $subscription->activate();

        // Logging
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_PAY_SUCCESS, $this->uid, [
            'amount' => $this->formattedTotal(),
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
        $chargeInfo = trans('messages.bill.charge_now');
        $plan = $subscription->planGeneral;

        return $this->getBillingInfoBase($chargeInfo, $plan);
    }

    public function checkoutAfterPayFailed($error)
    {
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_PAY_FAILED, $this->uid, [
            'amount' => $this->formattedTotal(),
            'error' => $error,
        ]);
    }

    public function beforeCancel()
    {
        throw new \Exception('Sub đang new thì không thể xóa invoice!');
    }

    public function approveAfter()
    {
        // logging by type
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_ADMIN_APPROVE, $this->uid, [
            'amount' => $this->formattedTotal(),
        ]);
    }

    public function rejectAfter($error)
    {
        // logging by type
        $subscription = Subscription::findByUid($this->getMetadata()['subscription_uid']);
        SubscriptionFacade::log($subscription, SubscriptionLog::TYPE_ADMIN_REJECT, $this->uid, [
            'amount' => $this->formattedTotal(),
            'reason' => $error,
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
}
