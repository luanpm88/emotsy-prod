<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Subscriber;

class SubscriberPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('subscriber_management');
    }

    public function read(User $user, Subscriber $item)
    {
        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id;
    }

    public function create(User $user)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        // constraints are checked in MailListPolicy
        return true && !$user->isReadOnly();
    }

    public function update(User $user, Subscriber $item)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id && !$user->isReadOnly();
    }

    public function delete(User $user, Subscriber $item)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id && !$user->isReadOnly();
    }

    public function subscribe(User $user, Subscriber $subscriber)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        $customer = $user->customer;
        return $subscriber->mailList->customer_id == $customer->id && !$user->isReadOnly();
    }

    public function unsubscribe(User $user, Subscriber $item)
    {
        // RBAC check
        if ($user->hasPermission('account.read_only')) {
            return false;
        }

        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id && !$user->isReadOnly();
    }
}
