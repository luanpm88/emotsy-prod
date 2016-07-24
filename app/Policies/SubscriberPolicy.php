<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriberPolicy
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
    
    public function read(\Mailixa\Model\User $user, \Mailixa\Model\Subscriber $item)
    {
        return $item->user_id == $user->id || $user->userGroup->backend_access;
    }
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\Subscriber $item)
    {
        $max = $user->getOption("frontend", "subscriber_max");
        $max_per_list = $user->getOption("frontend", "subscriber_per_list_max");
        return ($user->id == $item->mailList->user_id && ($max > $user->subscribers()->count() || $max == -1) && ($max_per_list > $item->mailList->subscribers()->count() || $max_per_list == -1));
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Subscriber $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\Subscriber $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function subscribe(\Mailixa\Model\User $user, \Mailixa\Model\Subscriber $item)
    {
        return $item->user_id == $user->id && $item->status == 'unsubscribed';
    }
    
    public function unsubscribe(\Mailixa\Model\User $user, \Mailixa\Model\Subscriber $item)
    {
        return $item->user_id == $user->id && $item->status == 'subscribed';
    }
}
