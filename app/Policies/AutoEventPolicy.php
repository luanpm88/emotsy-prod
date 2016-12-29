<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AutoEventPolicy
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
    
    public function disable(\Acelle\Model\User $user, \Acelle\Model\AutoEvent $event)
    {
        return $event->automation->user_id == $user->id && in_array($event->status, [
                                                                \Acelle\Model\AutoEvent::STATUS_ACTIVE
                                                            ]);
    }
    
    public function enable(\Acelle\Model\User $user, \Acelle\Model\AutoEvent $event)
    {
        return $event->automation->user_id == $user->id && in_array($event->status, [
                                                                \Acelle\Model\AutoEvent::STATUS_INACTIVE
                                                            ]);
    }
}
