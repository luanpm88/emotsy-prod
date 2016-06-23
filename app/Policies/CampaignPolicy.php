<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return true;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id;
    }
}
