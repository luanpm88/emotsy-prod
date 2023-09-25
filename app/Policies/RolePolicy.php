<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $can = true;

        return $can;
    }

    public function update(User $user, Role $role)
    {
        $can = !$role->default;

        return $can;
    }

    public function enable(User $user, Role $role)
    {
        $can = !$role->default;

        //
        $can = $can && !$role->isActive();

        return $can;
    }

    public function disable(User $user, Role $role)
    {
        $can = !$role->default;

        //
        $can = $can && $role->isActive();

        return $can;
    }

    public function delete(User $user, Role $role)
    {
        $can = !$role->default;

        return $can;
    }
}
