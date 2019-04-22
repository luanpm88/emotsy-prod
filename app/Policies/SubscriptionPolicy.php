<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Setting;
use Acelle\Cashier\Subscription;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    public function readAll(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $user->admin->getPermission('subscription_read') == 'all';
                break;
            case 'customer':
                $can = false;
                break;
        }

        return $can;
    }

    public function read(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $user->admin->getPermission('subscription_read') != 'no';
                break;
            case 'customer':
                $can = !$subscription->id || $user->customer->id == $subscription->customer_id;
                break;
        }

        return $can;
    }

    public function cancel(User $user, Subscription $subscription, $role)
    {
        $can = isset($subscription) && (!$subscription->cancelled() || $subscription->recurring());
        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                break;
        }

        return $can;
    }

    public function resume(User $user, Subscription $subscription, $role)
    {
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        $can = $gateway->isSupportRecurring() && isset($subscription) && $subscription->cancelled() && !$subscription->ended();

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                break;
        }

        return $can;
    }

    public function cancelNow(User $user, Subscription $subscription, $role)
    {
        $can = isset($subscription) && !$subscription->ended();

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                break;
        }

        return $can;
    }

    public function invoices(User $user, Subscription $subscription, $role)
    {
        $can = isset($subscription);

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                break;
        }

        return $can;
    }

    public function changePlan(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = false;
                break;
            case 'customer':
                $can = isset($subscription) && !$subscription->ended();
                break;
        }

        return $can;
    }

    public function renew(User $user, Subscription $subscription, $role)
    {
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $can = isset($subscription) && $subscription->active() && !$gateway->isSupportRecurring();
        $can = $can && (Setting::get('system.renew_free_plan') == 'yes' || (Setting::get('system.renew_free_plan') == 'no' && !$subscription->plan->isFree()));

        $lastDays = Setting::get('system.end_period_last_days');
        $goingToExpire = $subscription->goingToExpire($lastDays);

        $can = $can && $goingToExpire;

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                break;
        }

        return $can;
    }

    ////
    public function create(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = false;
                break;
            case 'customer':
                $can = !isset($subscription);
                break;
        }

        return $can;
    }

    public function subscribe(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = false;
                break;
            case 'customer':
                $can = $subscription->isPending();
                break;
        }

        return $can;
    }

    public function checkout(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = false;
                break;
            case 'customer':
                $can = $subscription->isNew();
                break;
        }

        return $can;
    }

    public function updateCard(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = false;
                break;
            case 'customer':
                $can = $subscription->isPending();
                break;
        }

        return $can;
    }

    public function setDone(User $user, Subscription $subscription, $role)
    {
        $can = $subscription->isPending();

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                $can = false;
                break;
        }

        return $can;
    }

    public function approvePendingInvoice(User $user, Subscription $subscription, $role)
    {
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $can = $subscription->checkPendingPaymentForFuture($gateway);

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                $can = false;
                break;
        }

        return $can;
    }

    public function delete(User $user, Subscription $subscription, $role)
    {
        $can = isset($subscription) && $subscription->ended();

        switch ($role) {
            case 'admin':
                break;
            case 'customer':
                break;
        }

        return $can;
    }
}
