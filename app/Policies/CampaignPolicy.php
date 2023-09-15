<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Campaign;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function read(User $user, Campaign $item)
    {
        $can = $item->customer_id == $user->customer->id;

        return $can;
    }

    public function create(User $user, Campaign $item)
    {
        $max = get_tmp_quota($user->customer, 'campaign_max');

        $can = $max > $user->customer->campaigns()->count() || $max == -1;

        // config/limit.php
        $limit = app_profile('campaign.limit');
        if (!is_null($limit)) {
            $campaignsCount = $user->customer->campaignsCount();
            $can = $can && ($campaignsCount < $limit);
        } else {
            // ignore limit because it is null
        }

        return $can && !$user->isReadOnly();
    }

    public function overview(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && !$user->isReadOnly() && !$item->isWaitForApproval();
    }

    public function approve(User $user, Campaign $item)
    {
        $admin = $user->admin;
        return $admin && $item->isWaitForApproval();
    }

    public function reject(User $user, Campaign $item)
    {
        $admin = $user->admin;
        return $admin && $item->isWaitForApproval();
    }

    public function update(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id
            && (in_array($item->status, [
                Campaign::STATUS_NEW,
                Campaign::STATUS_REJECTED,
                Campaign::STATUS_QUEUING,
                Campaign::STATUS_QUEUED,
                Campaign::STATUS_ERROR,
                Campaign::STATUS_PAUSED,
                Campaign::STATUS_SCHEDULED,
            ])) && !$user->isReadOnly();
    }

    public function delete(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, [
            Campaign::STATUS_NEW,
            Campaign::STATUS_REJECTED,
            Campaign::STATUS_QUEUING,
            Campaign::STATUS_QUEUED,
            Campaign::STATUS_ERROR,
            Campaign::STATUS_PAUSED,
            Campaign::STATUS_DONE,
            Campaign::STATUS_SENDING,
            Campaign::STATUS_SCHEDULED,
        ]) && !$user->isReadOnly();
    }

    public function pause(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, [
            Campaign::STATUS_QUEUING,
            Campaign::STATUS_QUEUED,
            Campaign::STATUS_SENDING,
            Campaign::STATUS_SCHEDULED,
        ]) && !$user->isReadOnly();
    }

    public function run(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, [
            Campaign::STATUS_NEW,
            Campaign::STATUS_REJECTED,
        ]) && !$user->isReadOnly();
    }

    public function restart(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, [
            Campaign::STATUS_PAUSED,
            Campaign::STATUS_ERROR,
            Campaign::STATUS_SCHEDULED,
        ]) && !$user->isReadOnly();
    }

    public function sort(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function copy(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && !$user->isReadOnly();
    }

    public function preview(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function image(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function resend(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && ($item->isDone() || $item->isPaused()) && !$user->isReadOnly();
    }

    public function send_test_email(User $user, Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, [
            Campaign::STATUS_QUEUING,
            Campaign::STATUS_QUEUED,
            Campaign::STATUS_SENDING,
            Campaign::STATUS_ERROR,
            Campaign::STATUS_PAUSED,
            Campaign::STATUS_DONE,
            Campaign::STATUS_SCHEDULED,
        ]) && !$user->isReadOnly();
    }
}
