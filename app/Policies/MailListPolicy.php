<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\MailList;

class MailListPolicy
{
    use HandlesAuthorization;

    public function read(User $user, MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function create(User $user)
    {
        $customer = $user->customer;
        $max = $customer->getOption('list_max');

        $can = $max > $customer->lists()->count() || $max == -1;

        // config/limit.php
        $limit = config('limit.list.limit');
        if (!is_null( $limit)) {
            $listsCount = $user->customer->listsCount();
            $can = $can && ($listsCount < $limit);
        } else {
            // ignore limit because it is null
        }

        return $can;
    }

    public function update(User $user, MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function delete(User $user, MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function addMoreSubscribers(User $user, MailList $mailList, $numberOfSubscribers = 1)
    {
        $max = $user->customer->getOption('subscriber_max');
        $maxPerList = $user->customer->getOption('subscriber_per_list_max');
        return $user->customer->id == $mailList->customer_id &&
            ($max >= $user->customer->subscribersCount() + $numberOfSubscribers || $max == -1) &&
            ($maxPerList >= $mailList->subscribersCount() + $numberOfSubscribers || $maxPerList == -1);
    }

    public function import(User $user, MailList $item)
    {
        $customer = $user->customer;
        $can = $customer->getOption('list_import');

        return ($can == 'yes' && $item->customer_id == $customer->id);
    }

    public function export(User $user, MailList $item)
    {
        $customer = $user->customer;
        $can = $customer->getOption('list_export');

        return ($can == 'yes' && $item->customer_id == $customer->id);
    }
}
