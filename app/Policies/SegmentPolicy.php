<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Segment;

class SegmentPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        if (app_profile('list.disable_segment') === true) {
            return false;
        }

        return true;
    }

    public function create(User $user, Segment $item)
    {
        if (app_profile('list.disable_segment') === true) {
            return false;
        }

        $customer = $user->customer;
        $max_per_list = get_tmp_quota($customer, 'segment_per_list_max');

        //
        $can = $customer->id == $item->mailList->customer_id;

        //
        $can = $can && ($max_per_list > $item->mailList->segments()->count()
            || $max_per_list == -1) && !$user->isReadOnly();

        //
        $can = $can && $item->mailList->segments()->count() < $user->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('segmentation');

        return $can;
    }

    public function update(User $user, Segment $item)
    {
        if (app_profile('list.disable_segment') === true) {
            return false;
        }

        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id && !$user->isReadOnly();
    }

    public function delete(User $user, Segment $item)
    {
        if (app_profile('list.disable_segment') === true) {
            return false;
        }

        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id && !$user->isReadOnly();
    }

    public function export(User $user, Segment $item)
    {
        if (app_profile('list.disable_segment') === true) {
            return false;
        }

        $customer = $user->customer;
        return $item->mailList->customer_id == $customer->id;
    }
}
