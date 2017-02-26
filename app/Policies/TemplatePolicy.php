<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_read') != 'no';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }
    
    public function read_all(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_read') == 'all';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_create') == 'yes';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || true;
        }
        
        return $can;
    }

    public function view(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_read');
            $ability = $ability == 'all'
                || ($ability == 'own' && $admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_update');
            $ability = $ability == 'all'
                || ($ability == 'own' && $admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function image(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_read');
            $ability = $ability == 'all'
                || ($ability == 'own' && $admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_delete');
            $ability = $ability == 'all'
                || ($ability == 'own' && $admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function preview(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_read');
            $ability = $ability == 'all'
                || ($ability == 'own' && $admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }

    public function saveImage(\Acelle\Model\User $user, \Acelle\Model\Template $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('template_update');
            $ability = $ability == 'all'
                || ($ability == 'own' && $admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || $customer->id == $item->customer_id;
        }
        
        return $can;
    }
}
