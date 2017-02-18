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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\Subscription $item)
    {
        $can = true; // $user->admin->getPermission('subscription_read') != 'no';

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

    public function update(\Acelle\Model\User $user, \Acelle\Model\Subscription $item)
    {
        // $ability = $user->admin->getPermission('subscription_update');
        $can = true; // $ability == 'all'
                // || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Subscription $item)
    {
        // $ability = $user->admin->getPermission('subscription_delete');
        $can = true; // $ability == 'all'
                //|| ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }
}
