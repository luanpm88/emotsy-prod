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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\User $item)
    {
        $can = $user->getOption("backend", "user_create") == 'yes';
        return $can;
    }
    
    public function profile(\Mailixa\Model\User $user, \Mailixa\Model\User $item)
    {
        return $user->id == $item->id;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\User $item)
    {
        $ability = $user->getOption("backend", "user_update");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        return $can;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\User $item)
    {
        $ability = $user->getOption("backend", "user_delete");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        $can = $can && $item->lists()->count() == 0 && $item->user_id != null;
        return $can;
    }
    
    public function switch(\Mailixa\Model\User $user, \Mailixa\Model\User $item)
    {
        $ability = $user->getOption("backend", "user_switch");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        return $can;
    }
}
