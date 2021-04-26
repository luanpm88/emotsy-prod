<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Setting;
use Acelle\Cashier\Cashier;
use Acelle\Model\Invoice;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function cancel(User $user, Invoice $invoice, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $invoice->isWait();
                break;
            case 'customer':
                $can = $invoice->isWait() && $invoice->customer_id == $user->customer->id;
                break;
        }

        return $can;
    }
}
