<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ResellerGroupPolicy
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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\ResellerGroup $item)
    {
        $can = $user->admin->getPermission('reseller_group_read') != 'no';

        return $can;
    }
    
    public function read_all(\Acelle\Model\User $user, \Acelle\Model\ResellerGroup $item)
    {
        $can = $user->admin->getPermission('reseller_group_read') == 'all';

        return $can;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\ResellerGroup $item)
    {
        $can = $user->admin->getPermission('reseller_group_create') == 'yes';

        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\ResellerGroup $item)
    {
        $ability = $user->admin->getPermission('admin_group_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function sort(\Acelle\Model\User $user, \Acelle\Model\ResellerGroup $item)
    {
        $ability = $user->admin->getPermission('reseller_group_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\ResellerGroup $item)
    {
        $ability = $user->admin->getPermission('reseller_group_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }
}
