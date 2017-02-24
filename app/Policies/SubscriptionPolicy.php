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
        $can = !$subscription->id;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $can = $can || true;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $subscription->customer_id;
        }
        
        return $can;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Subscription $item)
    {
        $can = true; // $user->admin->getPermission('subscription_create') == 'yes';

        return $can;
    }

    public function profile(\Acelle\Model\User $user, \Acelle\Model\Subscription $item)
    {
        return true; // $user->id == $item->user_id;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $can = $can || true;
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
            $can = $can || true;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can ||
                ( $subscription->status == \Acelle\Model\Subscription::STATUS_ACTIVE &&
                 $customer->id == $subscription->customer_id );
        }
        
        return $can;
    }
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
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
        
        $can = $can && $subscription->status != \Acelle\Model\Subscription::STATUS_INACTIVE;
        
        return $can;
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\Subscription $subscription)
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
        
        $can = $can && $subscription->status != \Acelle\Model\Subscription::STATUS_ACTIVE;
        
        return $can;
    }
}
