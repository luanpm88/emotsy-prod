<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackLoopHandlerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\FeedbackLoopHandler $item)
    {
        $can = $user->admin->getPermission('fbl_handler_read') != 'no';

        return $can;
    }
    
    public function read_all(\Acelle\Model\User $user, \Acelle\Model\FeedbackLoopHandler $item)
    {
        $can = $user->admin->getPermission('fbl_handler_read') == 'all';

        return $can;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\FeedbackLoopHandler $item)
    {
        $can = $user->admin->getPermission('fbl_handler_create') == 'yes';

        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\FeedbackLoopHandler $item)
    {
        $ability = $user->admin->getPermission('fbl_handler_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\FeedbackLoopHandler $item)
    {
        $ability = $user->admin->getPermission('fbl_handler_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }
}
