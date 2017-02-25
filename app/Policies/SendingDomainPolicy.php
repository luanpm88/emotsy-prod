<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SendingDomainPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_domain_read') != 'no';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || true;
        }
        
        return $can;
    }
    
    public function read_all(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_domain_read') == 'all';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || false;
        }
        
        return $can;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_domain_create') == 'yes';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || true;
        }
        
        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_domain_update');
            $ability = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_domain_delete');
            $ability = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }
}
