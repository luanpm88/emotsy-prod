<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function readAll(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_read') == 'all';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || false;
        }
        
        return $can;
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_read') != 'no';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || (!$subscription->id || $customer->id == $subscription->customer_id);
        }
        
        return $can;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Subscription $item, $role)
    {
        switch ($role) {
            case 'admin':
                $can = $user->admin->getPermission('subscription_create') == 'yes';
                break;
            case 'customer':
                $can = ($user->customer->getCurrentSubscription() && !$user->customer->getNextSubscription()) ||
                    $user->customer->getNotOutdatedSubscriptions()->count() == 0;
                break;
        }
        
        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_update');
            $ability = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->customer->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || false;
        }
        
        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription, $role)
    {
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('subscription_delete');
                $can = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->customer->admin_id);
                break;
            case 'customer':
                $can = $user->customer->id == $subscription->customer_id &&
                    $subscription->status == \Acelle\Model\Subscription::STATUS_INACTIVE;
                break;
        }
        
        return $can;
    }
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_disable');
            $ability = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->customer->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $subscription->customer_id;
        }
        
        $can = $can && $subscription->status == \Acelle\Model\Subscription::STATUS_ACTIVE;
        
        return $can;
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_enable');
            $ability = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->customer->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || false;
        }
        
        $can = $can && in_array($subscription->status, [\Acelle\Model\Subscription::STATUS_INACTIVE, \Acelle\Model\Subscription::STATUS_DISABLED]);
        
        return $can;
    }
    
    public function pay(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        return !$subscription->isPaid();
    }
    
    public function paid(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_paid');
            $ability = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->customer->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $subscription->customer_id;
        }
        
        $can = $can && $subscription->paid == false;
        
        return $can;
    }
    
    public function unpaid(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_unpaid');
            $ability = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->customer->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $subscription->customer_id;
        }
        
        $can = $can && $subscription->paid == true;
        
        return $can;
    }
}
