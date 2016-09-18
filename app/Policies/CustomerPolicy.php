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
        return true;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        return true;
    }

    public function profile(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        return $user->id == $item->user_id;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        return true;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        return true;
    }

    public function switch_customer(\Acelle\Model\User $user, \Acelle\Model\Customer $item)
    {
        return true;
    }
}
