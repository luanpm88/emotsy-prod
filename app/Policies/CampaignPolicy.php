<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function read(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        $max = $customer->getOption('campaign_max');

        return $max > $customer->campaigns()->count() || $max == -1;
    }

    public function overview(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && $item->status != 'new';
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, ['new', 'ready', 'error']);
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, ['new', 'ready', 'paused', 'done', 'sending']);
    }
    public function pause(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return false && $item->customer_id == $customer->id && in_array($item->status, ['sending', 'ready']);
    }
    public function restart(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id && in_array($item->status, ['error']);
    }
    
    public function sort(\Acelle\Model\User $user, \Acelle\Model\Campaign $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }
}
