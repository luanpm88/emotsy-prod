<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
    
    public function update(\Mailixa\User $user, \Mailixa\User $item)
    {
        return $user->id == $item->id;
    }
}
