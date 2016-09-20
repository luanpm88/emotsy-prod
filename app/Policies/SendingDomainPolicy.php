<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SendingDomainPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }
    
    public function read(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = $user->admin->getPermission('sending_domain_read') != 'no';

        return $can;
    }
    
    public function read_all(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = $user->admin->getPermission('sending_domain_read') == 'all';

        return $can;
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $can = $user->admin->getPermission('sending_domain_create') == 'yes';

        return $can;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $ability = $user->admin->getPermission('sending_domain_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\SendingDomain $item)
    {
        $ability = $user->admin->getPermission('sending_domain_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->admin->id == $item->admin_id);

        return $can;
    }
}
