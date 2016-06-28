<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserGroupPolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\UserGroup $item)
    {
        return true;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\UserGroup $item)
    {
        return $user->id == $item->user_id || $item->user_id == null;
    }
    
    public function sort(\Mailixa\Model\User $user, \Mailixa\Model\UserGroup $item)
    {
        return true;
    }
}
