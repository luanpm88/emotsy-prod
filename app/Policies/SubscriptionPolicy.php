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
            $can = $can || true;
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
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Subscription $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_create') == 'yes';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $ability = !is_object($customer->getCurrentInactiveSubscription());
            $can = $can || $ability;
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
                    || ($ability == 'own' && $user->admin->id == $subscription->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can ||
                ( $subscription->status == \Acelle\Model\Subscription::STATUS_ACTIVE &&
                 $customer->id == $subscription->customer_id );
        }
        
        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $user->admin->getPermission('subscription_delete');
            $ability = $ability == 'all'
                    || ($ability == 'own' && $user->admin->id == $subscription->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can ||
                ( $subscription->status == \Acelle\Model\Subscription::STATUS_INACTIVE &&
                 $customer->id == $subscription->customer_id );
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
                    || ($ability == 'own' && $user->admin->id == $subscription->admin_id);
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
                    || ($ability == 'own' && $user->admin->id == $subscription->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || false;
        }
        
        $can = $can && $subscription->status == \Acelle\Model\Subscription::STATUS_INACTIVE;
        
        return $can;
    }
}
