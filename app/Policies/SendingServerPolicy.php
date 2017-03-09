<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SendingServerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_read') != 'no';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $ability = $customer->getOption('create_sending_servers') == 'yes';
            $can = $can || $ability;
        }
        
        return $can;
    }
    
    public function read_all(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {    
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_read') == 'all';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $can = $can || false;
        }
        
        return $can;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {    
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_create') == 'yes';
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $max = $customer->getOption('sending_servers_max');
            $ability = $customer->getOption('create_sending_servers') == 'yes'
                && ($customer->sendingServersCount() < $max || $max == -1);
            $can = $can || $ability;
        }
        
        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_update');
            $ability = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $ability = $customer->getOption('create_sending_servers') == 'yes' && $customer->id == $item->customer_id;
            $can = $can || $ability;
        }
        
        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_delete');
            $ability = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $ability = $customer->getOption('create_sending_servers') == 'yes' && $customer->id == $item->customer_id;
            $can = $can || $ability;
        }
        
        return $can;
    }
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_update');
            $ability = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $ability = $customer->getOption('create_sending_servers') == 'yes' && $customer->id == $item->customer_id;
            $can = $can || $ability;
        }
        
        return ($can && $item->status != "inactive");
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        $can = false;
        
        $admin = $user->admin;
        if (is_object($admin)) {
            $ability = $admin->getPermission('sending_server_update');
            $ability = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);
            $can = $can || $ability;
        }
        
        $customer = $user->customer;
        if (is_object($customer)) {
            $ability = $customer->getOption('create_sending_servers') == 'yes' && $customer->id == $item->customer_id;
            $can = $can || $ability;
        }
        
        return ($can && $item->status != "active");
    }
}
