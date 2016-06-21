<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\Template $item)
    {
        return true;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Template $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function image(\Mailixa\Model\User $user, \Mailixa\Model\Template $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\Template $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function preview(\Mailixa\Model\User $user, \Mailixa\Model\Template $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function saveImage(\Mailixa\Model\User $user, \Mailixa\Model\Template $item)
    {
        return $item->user_id == $user->id;
    }
}
