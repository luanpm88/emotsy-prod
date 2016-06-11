<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
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
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Contact $item)
    {
        return $user->contact_id == $item->id;
    }
}
