<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SendingDomainPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(\Mailixa\Model\User $user, \Mailixa\Model\SendingDomain $item)
    {
        $can = $user->getOption('backend', 'sending_domain_create') == 'yes';

        return $can;
    }

    public function update(\Mailixa\Model\User $user, \Mailixa\Model\SendingDomain $item)
    {
        $ability = $user->getOption('backend', 'sending_domain_update');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }

    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\SendingDomain $item)
    {
        $ability = $user->getOption('backend', 'sending_domain_delete');
        $can = $ability == 'all'
                || ($ability == 'own' && $user->id == $item->user_id);

        return $can;
    }
}
