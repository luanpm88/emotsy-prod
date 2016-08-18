<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function read(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id || $user->userGroup->backend_access;
    }

    public function create(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        $max = $user->getOption('frontend', 'campaign_max');

        return $max > $user->campaigns()->count() || $max == -1;
    }

    public function overview(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id && $item->status != 'new';
    }

    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id && in_array($item->status, ["new","ready","error"]);
    }

    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id && in_array($item->status, ["new","ready","paused","done"]);
    }
    public function pause(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id && in_array($item->status, ["sending","ready"]);
    }
    public function restart(\Mailixa\Model\User $user, \Mailixa\Model\Campaign $item)
    {
        return $item->user_id == $user->id && in_array($item->status, ["done","paused"]);
    }
}
