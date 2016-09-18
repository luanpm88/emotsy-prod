<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminGroupPolicy
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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\AdminGroup $item)
    {
        return true;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\AdminGroup $item)
    {
        return true;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\AdminGroup $item)
    {
        return true;
    }

    public function sort(\Acelle\Model\User $user, \Acelle\Model\AdminGroup $item)
    {
        return true;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\AdminGroup $item)
    {
        return true;
    }
}
