<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SegmentPolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\Segment $item)
    {
        return true;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Segment $item)
    {
        return $item->mailList->user_id == $user->id;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\Segment $item)
    {
        return $item->mailList->user_id == $user->id;
    }
}
