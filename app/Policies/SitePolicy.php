<?php

namespace Acelle\Policies;

use Acelle\Model\User;
use Acelle\Model\Site;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return true;
    }

    public function read(User $user, Site $site)
    {
        return $user->customer->id == $site->customer_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Site $site)
    {
        return $user->customer->id == $site->customer_id;
    }

    public function enable(User $user, Site $site)
    {
        return $user->customer->id == $site->customer_id && $site->isInactive();
    }

    public function disable(User $user, Site $site)
    {
        return $user->customer->id == $site->customer_id && $site->isActive();
    }

    public function delete(User $user, Site $site)
    {
        return $user->customer->id == $site->customer_id;
    }

    public function verify(User $user, Site $site)
    {
        return $user->customer->id == $site->customer_id;
    }
}
