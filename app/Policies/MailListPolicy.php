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
        $max = $user->getOption("frontend", "list_max");
        return ($max > $user->lists()->count() || $max == -1);
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\MailList $item)
    {
        return $item->user_id == $user->id;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\MailList $item)
    {
        return $item->user_id == $user->id;
    }
}
