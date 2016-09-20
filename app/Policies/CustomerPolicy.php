<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $can = $user->admin->getPermission('customer_read') != 'no';

        return $can;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $can = $user->admin->getPermission('customer_create') == 'yes';

        return $can;
    }

    public function profile(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        return $user->id == $item->user_id;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $ability = $user->admin->getPermission('customer_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }

    public function loginAs(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $ability = $user->admin->getPermission('customer_login_as');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can && $user->customer->id != $item->id;
    }
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        $ability = $user->admin->getPermission('customer_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->creator_id);

        return $can;
    }
}
