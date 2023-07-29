<?php

namespace Acelle\Model;

use Acelle\Model\Invoice;
use Acelle\Library\Facades\SubscriptionFacade;
use Exception;

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

        // Tạm thời chỗ này phải if/else vì
        // chưa sử dụng class kế thừa cho subscription (VD: SubscriptionSender, SubscriptionGeneral, SubscriptionNumber...)
        // mà tất cả các loại plan trên đều dùng 1 class Subscription như nhau
        switch (get_class($subscription->plan->mapType())) {

            case PlanGeneral::class:

                // Set số lượng email max cho subscription
                // Hiện giờ code bên dưới đang error do $this->subscription đang null
                // ==> FIX bằng cách thêm field tường minh cho invoices table
                $subscription->setDefaultEmailCredits();

                break;

            default:

                throw new \Exception('Unknown plan type');

                break;
        }

        return;

        // @todo: move the log below to the paySuccess() method
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
