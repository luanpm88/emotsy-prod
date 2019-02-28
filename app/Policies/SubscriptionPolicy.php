<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
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
        $can = isset($subscription) && $subscription->cancelled() && !$subscription->ended();
        
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
    
    public function changePlan(User $user, Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $can = isset($subscription) && !$subscription->ended();
                break;
            case 'customer':
                $can = isset($subscription) && !$subscription->ended();
                break;
        }

        return $can;
    }
}
