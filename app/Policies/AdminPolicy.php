<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\Admin $item)
    {
        return true;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\Admin $item)
    {
        return true;
    }

    public function profile(\Acelle\Model\User $user, \Acelle\Model\Admin $item)
    {
        return $user->id == $item->user_id;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Admin $item)
    {
        return true;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Admin $item)
    {
        return true;
    }

    public function loginAs(\Acelle\Model\User $user, \Acelle\Model\Admin $item)
    {
        return true;
    }
}
