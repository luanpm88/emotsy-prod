<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\BounceHandler;

class BounceHandlerPolicy
{
    use HandlesAuthorization;

    public function read(User $user, BounceHandler $item)
    {
        $ability = $user->admin->getPermission('bounce_handler_read');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        // config/limit.php
        $enabled = config('limit.bounce_handler.disable') === false;
        $can = $can && $enabled;

        return $can;
    }

    public function readAll(User $user, BounceHandler $item)
    {
        $can = $user->admin->getPermission('bounce_handler_read') == 'all';

        // config/limit.php
        $enabled = config('limit.bounce_handler.disable') === false;
        $can = $can && $enabled;

        return $can;
    }

    public function create(User $user, BounceHandler $item)
    {
        $can = $user->admin->getPermission('bounce_handler_create') == 'yes';

        // config/limit.php
        $enabled = config('limit.bounce_handler.disable') === false;
        $can = $can && $enabled;

        return $can;
    }

    public function update(User $user, BounceHandler $item)
    {
        $ability = $user->admin->getPermission('bounce_handler_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        // config/limit.php
        $enabled = config('limit.bounce_handler.disable') === false;
        $can = $can && $enabled;

        return $can;
    }

    public function delete(User $user, BounceHandler $item)
    {
        $ability = $user->admin->getPermission('bounce_handler_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        // config/limit.php
        $enabled = config('limit.bounce_handler.disable') === false;
        $can = $can && $enabled;

        return $can;
    }

    public function test(User $user, BounceHandler $item)
    {
        $can = $this->update($user, $item) || !isset($item->id);

        // config/limit.php
        $enabled = config('limit.bounce_handler.disable') === false;
        $can = $can && $enabled;

        return $can;
    }
}
