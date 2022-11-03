<?php

namespace Acelle\Library;

use Acelle\Model\Subscription;

class SubscriptionManager {
    public function checkExpiration()
    {
        $subscriptions = Subscription::active()->get();

        foreach ($subscriptions as $subscription) {
            $subscription->checkExpiration();
        }
    }

    public function createRenewInvoice()
    {
        $subscriptions = Subscription::active()->get();

        foreach ($subscriptions as $subscription) {
            $subscription->checkAndCreateRenewInvoice();
        }
    }

    public function checkAndAutoPayRenewInvoiceByCustomer($customer)
    {
        $subscription = $customer->getCurrentActiveSubscription();

        if (!$subscription) {
            // do nothing
            return;
        }

        $unpaidRenewInvoice = $subscription->getItsOnlyUnpaidRenewInvoice();

        if (!$unpaidRenewInvoice) {
            // do nothing
            return;
        }

        // not reach due date
        if (!$subscription->reachDueDate()) {
            // do nothing
            return;
        }

        // check if customer can auto charge
        if (!$customer->preferredPaymentGatewayCanAutoCharge()) {
            return;
        }

        // auto charge
        $customer->getPreferredPaymentGateway()->autoCharge($unpaidRenewInvoice);
    }
}