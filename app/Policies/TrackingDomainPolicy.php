<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\TrackingDomain;
use Acelle\Model\PlanGeneral;

class TrackingDomainPolicy
{
    use HandlesAuthorization;

    public function read(User $user, TrackingDomain $item, $role)
    {
        return true;
    }

    public function create(User $user)
    {
        return true && !$user->isReadOnly();
    }

    public function update(User $user, TrackingDomain $item, $role)
    {
        return $user->customer->id == $item->customer_id && !$user->isReadOnly();
    }

    public function delete(User $user, TrackingDomain $item, $role)
    {
        return $user->customer->id == $item->customer_id && !$user->isReadOnly();
    }
}
