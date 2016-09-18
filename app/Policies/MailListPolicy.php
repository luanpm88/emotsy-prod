<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class MailListPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function read(\Acelle\Model\User $user, \Acelle\Model\MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\MailList $item)
    {
        $customer = $user->customer;
        $max = $customer->getOption('list_max');

        return $max > $customer->lists()->count() || $max == -1;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }
}
