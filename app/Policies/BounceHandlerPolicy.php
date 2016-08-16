<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class BounceHandlerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(\Mailixa\Model\User $user, \Mailixa\Model\BounceHandler $item)
    {
        $can = $user->getOption('backend', 'bounce_handler_create') == 'yes';

        return $can;
    }

    public function update(\Mailixa\Model\User $user, \Mailixa\Model\BounceHandler $item)
    {
        $ability = $user->getOption('backend', 'bounce_handler_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }

    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\BounceHandler $item)
    {
        $ability = $user->getOption('backend', 'bounce_handler_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }
}
