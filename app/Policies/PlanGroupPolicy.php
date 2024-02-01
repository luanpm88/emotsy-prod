<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\PlanGroup;

class PlanGroupPolicy
{
    use HandlesAuthorization;

    public function read(User $user)
    {
        $can = true;

        return $can;
    }

    public function readAll(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function create(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function update(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function delete(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function disable(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function enable(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function visibleOn(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function visibleOff(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }

    public function copy(User $user, PlanGroup $item)
    {
        $can = true;

        return $can;
    }
}
