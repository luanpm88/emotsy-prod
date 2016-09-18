<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerGroupPolicy
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
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\CustomerGroup $item)
    {
        return true;
    }
    
    public function create(\Acelle\Model\User $user, \Acelle\Model\CustomerGroup $item)
    {
        return true;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\CustomerGroup $item)
    {
        return true;
    }

    public function sort(\Acelle\Model\User $user, \Acelle\Model\CustomerGroup $item)
    {
        return true;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\CustomerGroup $item)
    {
        return true;
    }
}
