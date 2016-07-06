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
        $can = $user->getOption("backend", "user_group_create") == 'yes';
        return $can;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\UserGroup $item)
    {
        $ability = $user->getOption("backend", "user_group_update");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        return $can;
    }
    
    public function sort(\Mailixa\Model\User $user, \Mailixa\Model\UserGroup $item)
    {
        $ability = $user->getOption("backend", "user_group_update");
        $can = $ability == 'all';
        return $can;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\UserGroup $item)
    {
        $ability = $user->getOption("backend", "user_group_delete");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        $can = $can && $item->users()->count() == 0 && $item->user_id != null;
        return $can;
    }
}
