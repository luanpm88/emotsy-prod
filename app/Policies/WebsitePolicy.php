<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Website;

class WebsitePolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        if (app_profile('website.disable') === true) {
            return false;
        }

        return true;
    }

    public function create(User $user)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        if (app_profile('website.disable') === true) {
            return false;
        }

        return true && !$user->isReadOnly();
    }

    public function read(User $user, Website $website)
    {
        if (app_profile('website.disable') === true) {
            return false;
        }

        return $user->customer->id == $website->customer_id && !$user->isReadOnly();
    }

    public function update(User $user, Website $website)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        if (app_profile('website.disable') === true) {
            return false;
        }

        return $user->customer->id == $website->customer_id && !$user->isReadOnly();
    }

    public function delete(User $user, Website $website)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        if (app_profile('website.disable') === true) {
            return false;
        }

        return $user->customer->id == $website->customer_id && !$user->isReadOnly();
    }

    public function connect(User $user, Website $website)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        if (app_profile('website.disable') === true) {
            return false;
        }

        return $user->customer->id == $website->customer_id && $website->status == Website::STATUS_INACTIVE && !$user->isReadOnly();
    }

    public function disconnect(User $user, Website $website)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        if (app_profile('website.disable') === true) {
            return false;
        }

        return $user->customer->id == $website->customer_id && $website->status == Website::STATUS_CONNECTED && !$user->isReadOnly();
    }
}
