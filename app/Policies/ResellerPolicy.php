<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ResellerPolicy
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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $can = $user->admin->getPermission('reseller_read') != 'no';

        return $can;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $can = $user->admin->getPermission('reseller_create') == 'yes';

        return $can;
    }

    public function profile(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        return $user->id == $item->user_id;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $ability = $user->admin->getPermission('reseller_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $ability = $user->admin->getPermission('reseller_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function loginAs(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $ability = $user->admin->getPermission('reseller_login_as');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can && $user->admin->id != $item->id;
    }
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $ability = $user->admin->getPermission('reseller_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can && $item->status != 'inactive';
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\Reseller $item)
    {
        $ability = $user->admin->getPermission('reseller_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can && $item->status != 'active';
    }
}
