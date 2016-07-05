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
        $max_per_list = $user->getOption("frontend", "segment_per_list_max");
        return ($user->id == $item->mailList->user_id && ($max_per_list > $item->mailList->segments()->count() || $max_per_list == -1));
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
