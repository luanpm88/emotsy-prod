<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SendingServerPolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\SendingServer $item)
    {
        $can = $user->getOption("backend", "sending_server_create") == 'yes';
        return $can;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\SendingServer $item)
    {
        $ability = $user->getOption("backend", "sending_server_update");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        return $can;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\SendingServer $item)
    {
        $ability = $user->getOption("backend", "sending_server_delete");
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);
        return $can;
    }
}
