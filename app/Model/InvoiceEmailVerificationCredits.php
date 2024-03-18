<?php

namespace Acelle\Model;

use Acelle\Model\Invoice;
use Acelle\Model\Customer;

class InvoiceEmailVerificationCredits extends Invoice
{
    protected $table = 'invoices';

    public const TYPE_EMAIL_VERIFICATION_CREDITS = 'email_verification_credits';

    /**
     * Process invoice.
     *
     * @return void
     */
    public function process()
    {
        // add credits to customer......
        // $this->email_credits 1000
    }

    /**
     * Get billing info.
     *
     * @return void
     */
    public function getBillingInfo()
    {
        $chargeInfo = trans('messages.bill.charge_now');

        return $this->getBillingInfoBase($chargeInfo);
    }

    public function refreshPrice()
    {
        if ($this->isPaid()) {
            throw new \Exception("Can not change paid invoice price!");
        }

        // do nothing
    }

    public function beforeCancel()
    {
    }

    public function checkoutAfterPayFailed($error)
    {
    }
}
