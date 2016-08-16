<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class LayoutPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Layout $item)
    {
        $ability = $user->getOption('backend', 'layout_update');
        $can = $ability == 'yes';

        return $can;
    }
}
