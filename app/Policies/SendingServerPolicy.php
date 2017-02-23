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
    
    public function readAll(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $ability = $user->getOption('backend', 'sending_server_read');
        $can = $ability == 'all';

        return $can;
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $ability = $user->getOption('backend', 'user_group_read');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $can = $user->getOption('backend', 'sending_server_create') == 'yes';

        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $ability = $user->getOption('backend', 'sending_server_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $ability = $user->getOption('backend', 'sending_server_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $ability = $user->getOption('backend', 'sending_server_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return ($can && $item->status != "inactive");
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\SendingServer $item)
    {
        if(!$user->userGroup->backend_access) {
            return false;
        }
        
        $ability = $user->getOption('backend', 'sending_server_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return ($can && $item->status != "active");
    }
}
