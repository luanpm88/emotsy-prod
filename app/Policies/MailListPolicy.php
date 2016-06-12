<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class MailListPolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\MailList $item)
    {
        return true;
    }
}
