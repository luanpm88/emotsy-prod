<?php

namespace Acelle\Library;

use Acelle\Model\Subscription;
use Acelle\Cashier\Library\TransactionVerificationResult;
use Acelle\Model\SubscriptionLog;

class SubscriptionManager
{
    // Scan ACTIVE subscriptions and
    // - Create renew invoice OR
    // - End it
    public function endExpiredSubscriptions()
    {
        $subscriptions = Subscription::active()->get();

        foreach ($subscriptions as $subscription) {
            $subscription->endIfExpired();
        }
    }

    public function createRenewInvoices()
    {
        $subscriptions = Subscription::active()->get();

        foreach ($subscriptions as $subscription) {
            $subscription->checkAndCreateRenewInvoice();
        }
    }

    public function autoChargeRenewInvoices()
    {
        $renewInvoices = \Acelle\Model\InvoiceRenewSubscription::unpaid()->get();

        foreach ($renewInvoices as $invoice) {
            $subscription = $invoice->subscription;
            $customer = $subscription->customer;

            // not reach due date
            if (!$subscription->reachDueDate()) {
                // do nothing
                continue;
            }

            // check if customer can auto charge
            if (!$customer->preferredPaymentGatewayCanAutoCharge()) {
                continue;
            }

            // check if invoice amount is 0
            if ($invoice->total() == 0) {
                // Trường hợp invoice total = 0 thì pay nothing và set done luôn cho renew invoice
                $invoice->checkout($customer->getPreferredPaymentGateway(), function ($invoice) {
                    return new TransactionVerificationResult(TransactionVerificationResult::RESULT_DONE);
                });

                continue;
            }

            // auto charge
            $customer->getPreferredPaymentGateway()->autoCharge($invoice);
        }

    }

    public function log($subscription, $type, $invoice_uid=null, $metadata=[])
    {
        $log = $subscription->subscriptionLogs()->create([
            'type' => $type,
            'invoice_uid' => $invoice_uid,
        ]);

        if (isset($metadata)) {
            $log->updateData($metadata);
        }

        return $log;
    }
}
